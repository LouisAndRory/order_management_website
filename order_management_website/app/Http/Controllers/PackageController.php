<?php

namespace App\Http\Controllers;

use App\Http\Requests\Package\StoreRequest;
use App\Http\Requests\Package\UpdateRequest;
use App\Models\Order;
use App\Services\Mutators\PackageMutator;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function store(StoreRequest $request)
    {
        info("PackageController@store", $request->all());

        $packageMutator = new PackageMutator();
        try {
            $package = $packageMutator->store($request->all());
            $order = Order::with([
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
            ])->find($package->order_id);

            return response()->json_created([
                'packages' => $order->packages,
            ]);
        } catch (\Exception $e) {
            return response()->json_create_failed();
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        info("PackageController@update", $request->all());

        $packageMutator = new PackageMutator();
        try {
            $package = $packageMutator->update($id, $request->all());
            $order = Order::with([
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
            ])->find($package->order_id);

            return response()->json_updated([
                'packages' => $order->packages,
            ]);
        } catch (\Exception $e) {
            return response()->json_update_failed();
        }
    }

    public function delete($id)
    {
        info("PackageController@delete", $id);

        $packageMutator = new PackageMutator();
        try {
            $packageMutator->delete($id);

            return response()->json_deleted();
        } catch (\Exception $e) {
            return response()->json_deleted_failed();
        }
    }
}
