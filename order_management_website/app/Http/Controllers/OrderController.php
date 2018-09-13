<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\CaseType;
use App\Models\Cookie;
use App\Models\Order;
use App\Models\Pack;
use App\Services\Mutators\OrderMutator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {

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
            'married_date', 'remark', 'card_required', 'wood_required'
        ])->with([
            'cases:id,order_id,case_type_id,price,amount',
            'cases.cookies'
        ])->findOrFail($id);

        $caseTypeIds = collect($order->cases)->pluck('case_type_id')->toArray();
        $caseTypes = CaseType::select([
            'id', 'name'
        ])->whereIn('id', $caseTypeIds)
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
        ])->whereIn('id', $cookieIds)->get();

        $packs = Pack::select([
            'id', 'name'
        ])->whereIn('id', $packIds)->get();

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
            'married_date', 'remark', 'card_required', 'wood_required'
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
        $caseTypes = CaseType::withoutGlobalScopes()
            ->select([
                'id', 'name'
            ])->whereIn('id', $caseTypeIds)
            ->get();

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
        info("OrderController@delete", $id);

        $orderMutator = new OrderMutator();
        try {
            $orderMutator->delete($id);

            return response()->json_deleted();
        } catch (\Exception $e) {
            return response()->json_deleted_failed();
        }
    }
}
