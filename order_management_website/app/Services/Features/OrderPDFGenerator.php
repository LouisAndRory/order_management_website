<?php

namespace App\Services\Features;


use App\Models\CaseModel;
use App\Models\Order;
use setasign\Fpdi\Tcpdf\Fpdi;

class OrderPDFGenerator
{
    protected $order;

    protected $fpdi;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->fpdi = new FPDI();

        $this->config();
        $this->loadData();
    }

    public function create()
    {
        $this->setBasic();
        $this->setPackage();
        $this->setCase();
        $this->setRemark();
        $this->setCaseContent();

        $this->fpdi->Output('test.pdf', 'D');
    }

    private function config()
    {
        $this->fpdi->setPrintFooter(false);
        $this->fpdi->setPrintHeader(false);
        $this->fpdi->setSourceFile(storage_path('app/pdf/order_template.pdf'));
        $this->fpdi->SetFont('microsoftjhenghei', '', 14, storage_path('app/pdf/fonts/microsoftjhenghei.php'));

        $tplIdx = $this->fpdi->importPage(1);
        $this->fpdi->AddPage();
        $this->fpdi->useTemplate($tplIdx);
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
    }

    private function setBasic()
    {
        $this->fpdi->SetFontSize(14);

        $this->fpdi->Text(33, 28, $this->order->name ?: "");
        $this->fpdi->Text(120, 28, $this->order->phone ?: "");
        $this->fpdi->Text(33, 40, $this->order->married_date ? $this->order->married_date->format('Y-m-d') : "");
        $this->fpdi->Text(120, 40, $this->order->email ?: "");
    }

    private function setPackage()
    {
        $packageData = [];
        foreach ($this->order->packages as $package) {
            $arrivedDate = $package->arrived_at ? $package->arrived_at->format('Y-m-d') : "";
            $remark = preg_replace("/\r|\n/", " ", $package->remark);

            $caseTypes = [];
            foreach ($package->cases as $content) {
                array_push($caseTypes, "$content->case_type_name $content->amount 盒");
            }

            array_push($packageData, "$arrivedDate " . implode('/', $caseTypes));
            array_push($packageData, "$package->address $package->name $package->phone");
            array_push($packageData, "$remark");
        }

        if (count($packageData) > 2) {
            $this->fpdi->SetFontSize(9);
        } else {
            $this->fpdi->SetFontSize(11);
        }

        foreach ($packageData as $key => $datum) {
            if ($datum) {
                $this->fpdi->Text(33, 50 + (4 * $key), $datum);
            }
        }
    }

    private function setCase()
    {
        $caseData = [
            "type" => [],
            "amount" => [],
            "price" => []
        ];

        $cases = CaseModel::select([
            'cases.id', 'case_type_id', 'case_types.name AS case_type_name', 'amount', 'price'
        ])->leftJoin('case_types', 'case_types.id', '=', 'cases.case_type_id')
            ->where('cases.order_id', $this->order->id)
            ->get();

        foreach ($cases as $case) {
            array_push($caseData['type'], $case->case_type_name ?: "");
            array_push($caseData['amount'], $case->amount);
            array_push($caseData['price'], $case->price);
        }

        $type = implode($caseData['type'], "/");
        $amount = implode($caseData['amount'], "/");
        $price = implode($caseData['price'], "/");

        if (strlen($type) > 16) {
            $this->fpdi->SetFontSize(9);
        } else {
            $this->fpdi->SetFontSize(12);
        }
        $this->fpdi->Text(33, 101, $type);

        $this->fpdi->SetFontSize(14);
        $this->fpdi->Text(120, 101, $amount);
        $this->fpdi->Text(33, 112, $price);
        $this->fpdi->Text(120, 112, $this->order->deposit);
    }

    private function setRemark()
    {
        $this->fpdi->SetFontSize(10);

        $maxTarget = 55;
        $remark = preg_replace("/\r|\n/", " ", trim($this->order->remark));
        if (strlen($remark) > $maxTarget) {
            preg_match_all('/./u', $remark, $matches);
            for ($i = 0; $i <= (count($matches[0]) / $maxTarget); $i++) {
                $value = "";
                $j_max = count($matches[0]) - ($maxTarget * $i) > $maxTarget ? $maxTarget : count($matches[0]) - ($maxTarget * $i);
                for ($j = 0; $j < $j_max; $j++) {
                    $value .= $matches[0][($maxTarget * $i) + $j];
                }
                $this->fpdi->Text(33, 122 + (4 * $i), $value);
            }

        } else {
            $this->fpdi->Text(33, 122, $remark);
        }
    }

    private function setCaseContent()
    {
        $cases = [];
        foreach ($this->order->cases as $case) {
            $cases[$case->case_type_name] = [
                "content" => []
            ];
            foreach ($case->cookies as $content) {
                if (!array_key_exists($content->pack_id, $cases[$case->case_type_name]["content"])) {
                    $cases[$case->case_type_name]["content"][$content->pack_id] = [
                        "pack_name" => $content->pack_name,
                        "cookies" => []
                    ];
                }
                array_push($cases[$case->case_type_name]["content"][$content->pack_id]["cookies"], "$content->cookie_name$content->amount");
            }
        }

        $y = 138;
        foreach ($cases as $caseTypeName => $case) {
            $this->fpdi->SetFontSize(12);
            $this->fpdi->Text(33, $y, $caseTypeName);
            $y += 6;

            foreach ($case as $key => $content) {
                if (!empty($content)) {
                    foreach ($content as $item) {
                        $this->fpdi->SetFontSize(11);
                        $this->fpdi->Text(33, $y, $item["pack_name"] . "-");
                        $y += 6;

                        $this->fpdi->Text(33, $y, implode(", ", $item['cookies']));
                        $y += 7;
                    }
                }
            }
        }
    }
}