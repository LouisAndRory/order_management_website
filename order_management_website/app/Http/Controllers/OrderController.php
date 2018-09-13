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

        //-- change format
        foreach ($order->cases as $case) {
            foreach ($case->cookies as $key => $cookie) {
                $case->cookies[$key] = $cookie->pivot;
            }
        }

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

    }

    public function store(StoreRequest $request)
    {
        info("OrderController@store", $request->all());

        $orderMutator = new OrderMutator();
        try {
            $orderMutator->store($request->all());

            return response()->json_created();
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
