<?php

namespace App\Http\Controllers;

use App\Http\Requests\Package\StoreRequest;
use App\Http\Requests\Package\UpdateRequest;
use App\Models\Order;
use App\Models\Package;
use App\Services\Features\PackageExport;
use App\Services\Mutators\PackageMutator;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function search()
    {
        $queries = request()->all();
        if (empty($queries)) {
            return view('package.search');
        }

        $isAllInputEmpty = collect($queries)->every(function ($value, $key) {
           return $value === null;
        });

        if ($isAllInputEmpty) {
            return back();
        }

        $packageOrm = Package::select([
            'packages.id', 'orders.id AS order_id', 'orders.name AS order_name', 'packages.phone AS package_phone',
            'orders.phone AS order_phone', 'arrived_at', 'sent_at', 'orders.married_date', 'checked'
        ])->join('orders', 'orders.id', '=', 'packages.order_id')
            ->whereNull('orders.deleted_at')
            ->with([
                'cases' => function ($q) {
                    $q->select([
                        'case_id', 'package_id', 'case_types.name' , 'package_has_cases.amount'
                    ])->join('cases', 'cases.id', '=', 'package_has_cases.case_id')
                        ->join('case_types', 'case_types.id', '=', 'cases.case_type_id');
                }
            ]);

        foreach ($queries as $key => $value) {
            if ($value) {
                if ($key === 'shipped') {
                    $packageOrm->where('packages.sent_at', '!=', null);
                } else if ($key === 'arrived_at_max') {
                    $packageOrm->where('packages.arrived_at', '<=', $value);
                } else if ($key === 'arrived_at_min') {
                    $packageOrm->where('packages.arrived_at', '>=', $value);
                } else if ($key === 'order_name') {
                    $packageOrm->where('orders.name', 'LIKE',  "%$value%");
                } else if ($key === 'order_phone') {
                    $packageOrm->where('orders.phone', 'LIKE',  "%$value%");
                } else if (\Schema::hasColumn('packages', $key)) {
                    $packageOrm->where("packages.$key", 'LIKE', "%$value%");
                }
            }
        }

        $packages = $packageOrm->orderBy('packages.arrived_at', 'ASC')->get();

        return view('package.search', [
            'packages' => $packages
        ]);
    }

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
        info("PackageController@delete: $id");

        $packageMutator = new PackageMutator();
        try {
            $packageMutator->delete($id);

            return response()->json_deleted();
        } catch (\Exception $e) {
            return response()->json_delete_failed();
        }
    }

    public function excel(Request $request)
    {
        $input = $request->all();
        if (empty($input)) {
            return response([
                'message' => 'Empty input'
            ], 400);
        }

        $export = new PackageExport($input);
        return $export->download();
    }
}
