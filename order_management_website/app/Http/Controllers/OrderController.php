<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\CaseType;
use App\Models\Cookie;
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

        return response()->json([
            'caseTypes' => $caseTypes,
            'cookies' => $cookies,
            'packs' => $packs
        ]);
    }

    public function edit($id)
    {

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
