<?php

namespace App\Http\Controllers;

use App\Http\Requests\Package\StoreRequest;
use App\Http\Requests\Package\UpdateRequest;
use App\Services\Mutators\PackageMutator;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function store(StoreRequest $request)
    {
        info("PackageController@store", $request->all());

        $packageMutator = new PackageMutator();
        try {
            $packageMutator->store($request->all());

            return response()->json_created();
        } catch (\Exception $e) {
            return response()->json_create_failed();
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        info("PackageController@update", $request->all());

        $packageMutator = new PackageMutator();
        try {
            $packageMutator->update($id, $request->all());

            return response()->json_updated();
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
