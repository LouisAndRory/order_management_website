<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\CaseType;
use App\Models\Cookie;
use App\Models\Order;
use App\Models\Pack;
use App\Models\Package;
use App\Services\Features\OrderPDFGenerator;
use App\Services\Features\OrderService;
use App\Services\Mutators\OrderMutator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $packages = Package::select([
            'packages.id AS package_id', 'orders.id AS order_id', 'packages.name AS package_name',
            'packages.arrived_at', 'packages.sent_at', 'packages.remark', 'orders.final_paid',

        ])->join('orders', 'orders.id', '=', 'packages.order_id')
            ->whereDate('arrived_at', now()->addDay()->toDateString())
            ->orderBy('arrived_at', 'ASC')
            ->get();

        return view('order.index', [
            'packages' => $packages
        ]);
    }

    public function search()
    {
        $queries = request()->all();
        if (empty($queries)) {
            return view('order.search');
        }

        $isAllInputEmpty = collect($queries)->every(function ($value, $key) {
            return $value === null;
        });

        if ($isAllInputEmpty) {
            return back();
        }

        $orderOrm = Order::select([
            'id', 'name', 'phone', 'final_paid', 'remark', 'married_date', 'fb'
        ]);

        foreach ($queries as $key => $value) {
            if ($value) {
                if ($key === 'final_paid') {
                    $orderOrm->where($key, true);
                } else if (\Schema::hasColumn('orders', $key)) {
                    $orderOrm->where($key, 'LIKE', "%$value%");
                }
            }
        }

        $orders = $orderOrm->get();

        return view('order.search', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $caseTypes = CaseType::select([
            'id', 'name'
        ])->get();

        $cookies = Cookie::select([
            'id', 'name'
        ])->get();

        $packs = Pack::select([
            'id', 'name'
        ])->get();

        return view('order.create', [
            'caseTypes' => $caseTypes,
            'cookies' => $cookies,
            'packs' => $packs
        ]);
    }

    public function edit($id)
    {
        $order = Order::select([
            'id', 'name', 'name_backup', 'phone', 'phone_backup',
            'email', 'deposit', 'extra_fee', 'final_paid', 'engaged_date',
            'married_date', 'remark', 'card_required', 'wood_required', 'fb'
        ])->with([
            'cases:id,order_id,case_type_id,price,amount',
            'cases.cookies'
        ])->findOrFail($id);

        $caseTypeIds = collect($order->cases)->pluck('case_type_id')->toArray();
        $caseTypes = CaseType::select([
            'id', 'name'
        ])->whereIn('id', $caseTypeIds)
            ->orWhere('enabled', '=', true)
            ->get();

        $packIds = collect([]);
        $cookieIds = collect([]);
        foreach ($order->cases as $case) {
            $cookieId = collect($case->cookies)->pluck('cookie_id')->toArray();
            $cookieIds = $cookieIds->concat($cookieId);

            $packId = collect($case->cookies)->pluck('pack_id')->toArray();
            $packIds = $packIds->concat($packId);
        }
        $cookieIds = $cookieIds->unique()->toArray();
        $packIds = $packIds->unique()->toArray();

        $cookies = Cookie::select([
            'id', 'name'
        ])->whereIn('id', $cookieIds)
            ->orWhere('enabled', '=', true)
            ->get();

        $packs = Pack::select([
            'id', 'name'
        ])->whereIn('id', $packIds)
            ->orWhere('enabled', '=', true)
            ->get();

        return view('order.edit', [
            'order' => $order,
            'caseTypes' => $caseTypes,
            'cookies' => $cookies,
            'packs' => $packs
        ]);
    }

    public function show($id)
    {
        $order = Order::select([
            'id', 'name', 'name_backup', 'phone', 'phone_backup',
            'email', 'deposit', 'extra_fee', 'final_paid', 'engaged_date',
            'married_date', 'remark', 'card_required', 'wood_required', 'fb'
        ])->with([
            'cases' => function ($q) {
                $q->select([
                    'cases.id', 'cases.order_id', 'case_types.name AS case_type_name',
                    'cases.amount', 'cases.price', 'cases.case_type_id AS case_type_id'
                ])->join('case_types', 'cases.case_type_id', '=', 'case_types.id')
                    ->with([
                        'cookies' => function ($q) {
                            $q->select([
                                'case_id', 'amount',
                                'cookies.name AS cookie_name', 'packs.name AS pack_name'
                            ])->join('packs', 'packs.id', '=', 'case_has_cookies.pack_id')
                                ->join('cookies', 'cookies.id', '=', 'case_has_cookies.cookie_id');
                        }
                    ]);
            },
            'packages' => function ($q) {
                $q->select([
                    'id', 'order_id', 'name', 'phone', 'address', 'remark',
                    'sent_at', 'arrived_at', 'checked'
                ])->with([
                    'cases' => function ($q) {
                        $q->select([
                            'package_has_cases.case_id', 'package_has_cases.amount', 'package_has_cases.package_id',
                            'case_types.name AS case_type_name'
                        ])->join('cases', 'cases.id', '=', 'package_has_cases.case_id')
                            ->join('case_types', 'case_types.id', '=', 'cases.case_type_id');
                    }
                ]);
            }
        ])->findOrFail($id);

        $caseTypeIds = $order->cases->pluck('case_type_id')->toArray();
        $caseTypes = CaseType::select([
            'id', 'name'
        ])->whereIn('id', $caseTypeIds)
            ->orWhere('enabled', '=', true)
            ->get();

        $orderService = new OrderService();
        $orderService->getTotalFeeAttribute($order);

        return view('order.show', [
            'order' => $order,
            'caseTypes' => $caseTypes
        ]);
    }

    public function store(StoreRequest $request)
    {
        info("OrderController@store", $request->all());

        $orderMutator = new OrderMutator();
        try {
            $order = $orderMutator->store($request->all());

            return response()->json_created([
                'id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json_create_failed();
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        info("OrderController@update", $request->all());

        $orderMutator = new OrderMutator();
        try {
            $orderMutator->update($id, $request->all());

            return response()->json_updated();
        } catch (\Exception $e) {
            return response()->json_update_failed();
        }
    }

    public function delete($id)
    {
        info("OrderController@delete: $id");

        $orderMutator = new OrderMutator();
        try {
            $orderMutator->delete($id);

            return response()->json_deleted();
        } catch (\Exception $e) {
            return response()->json_delete_failed();
        }
    }

    public function pdf($id)
    {
        $order = Order::findOrFail($id);

        $generator = new OrderPDFGenerator($order);
        return $generator->create('pdf');
    }

    public function image($id)
    {
        $order = Order::findOrFail($id);

        $generator = new OrderPDFGenerator($order);
        return $generator->create('image');
    }
}
