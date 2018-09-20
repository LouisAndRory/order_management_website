<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pack\StoreRequest;
use App\Http\Requests\Pack\UpdateRequest;
use App\Models\Pack;
use App\Services\Mutators\PackMutator;
use Illuminate\Http\Request;

class PackController extends Controller
{
    public function index()
    {
        $packs = Pack::select([
            'id', 'name', 'slug', 'enabled'
        ])->get();

        return view('management.packs',[
            'packs' => $packs
        ]);
    }

    public function store(StoreRequest $request)
    {
        $packMutator = new PackMutator();
        $packMutator->store($request->all());

        return response()->json_created();
    }

    public function update(UpdateRequest $request, $id)
    {
        $packMutator = new PackMutator();
        $packMutator->update($id, $request->all());

        return response()->json_updated();
    }

    public function delete($id)
    {
        $packMutator = new PackMutator();
        try {
            $packMutator->delete($id);
            return response()->json_deleted();
        } catch (\Exception $e) {
            return response()->json_delete_failed();
        }
    }
}
