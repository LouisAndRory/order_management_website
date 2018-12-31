<?php

namespace App\Services\Features;


use App\Models\Order;

class OrderPDFGenerator
{
    protected $order;

    private $cases = [];

    private $packages = [];

    public function __construct(Order $order)
    {
        $this->order = $order;

        $this->loadData();
        $this->loadCases();
        $this->loadPackages();
    }

    public function create()
    {
        $headerHtml = view()->make('order.pdf.header')->render();
        $footerHtml = view()->make('order.pdf.footer')->render();
        $dompdf = app('snappy.pdf.wrapper');
        $dompdf->loadView('order.pdf.index', [
            'order' => $this->order,
            'cases' => $this->cases,
            'packages' => $this->packages
        ])->setPaper('a4')
            ->setOption('margin-left', 0)
            ->setOption('margin-bottom', 6.3)
            ->setOption('margin-top', 30)
            ->setOption('margin-right', 0)
            ->setOption('header-html', $headerHtml)
            ->setOption('footer-html', $footerHtml)
            ->setWarnings(false);

        return $dompdf->download('order_' . $this->order->id . '.pdf');
        return view('order.pdf.index', [
            'order' => $this->order,
            'cases' => $this->cases,
            'packages' => $this->packages
        ]);
    }

    private function loadData()
    {
        $this->order->load([
            'cases' => function ($q) {
                $q->select([
                    'cases.id', 'cases.order_id', 'cases.case_type_id',
                    'case_types.name AS case_type_name', 'price', 'amount'
                ])->with([
                    'cookies' => function ($q) {
                        $q->select([
                            'case_id', 'amount', 'cookie_id', 'pack_id',
                            'cookies.name AS cookie_name', 'packs.name AS pack_name'
                        ])->join('cookies', 'case_has_cookies.cookie_id', '=', 'cookies.id')
                            ->join('packs', 'case_has_cookies.pack_id', '=', 'packs.id');
                    }
                ])->join('case_types', 'case_types.id', '=', 'cases.case_type_id');
            },
            'packages' => function ($q) {
                $q->select([
                    'id', 'order_id', 'name', 'phone', 'address', 'remark',
                    'sent_at', 'arrived_at'
                ])->with([
                    "cases" => function ($q) {
                        $q->select([
                            'package_has_cases.package_id', 'case_types.name AS case_type_name', 'package_has_cases.amount'
                        ])->join('cases', 'cases.id', '=', 'package_has_cases.case_id')
                            ->join('case_types', 'case_types.id', '=', 'cases.case_type_id');
                    }
                ]);
            }
        ]);

        $orderService = new OrderService();
        $orderService->getTotalFeeAttribute($this->order);
    }

    private function loadCases()
    {
        $cases = [];
        foreach ($this->order->cases as $case) {
            $content = [];
            $packNames = $case->cookies->pluck('pack_name')->unique()->values()->toArray();
            foreach ($packNames as $packName) {
                array_push($content, $packName . ' -');
                $cookieNames = [];

                foreach ($case->cookies as $cookie) {
                    if ($cookie->pack_name === $packName) {
                        array_push($cookieNames, "$cookie->cookie_name$cookie->amount");
                    }
                }
                array_push($content, implode(', ', $cookieNames));
            }

            array_push($cases, [
                'case_type_name' => $case->case_type_name,
                'amount' => $case->amount,
                'price' => $case->price,
                'content' => $content
            ]);
        }

        $this->cases = $cases;
    }

    private function loadPackages()
    {
        $packages = [];
        foreach ($this->order->packages as $package) {
            $caseTypes = [];
            foreach ($package->cases as $content) {
                array_push($caseTypes, "$content->case_type_name $content->amount 盒");
            }

            array_push($packages, [
                'arrived_at' => $package->arrived_at ? $package->arrived_at->format('Y-m-d') : '',
                'remark' => preg_replace("/\r|\n/", ' ', $package->remark),
                'case_type' => implode('/', $caseTypes),
                'address' => "$package->address $package->name $package->phone"
            ]);
        }

        $this->packages = $packages;
    }
}