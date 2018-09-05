<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseModel\StoreRequest;
use App\Http\Requests\CaseModel\UpdateRequest;
use App\Models\CaseModel;
use App\Services\Mutators\CaseMutator;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function index()
    {
        $cases = CaseModel::all();

        return response()->json([
            'cases' => $cases
        ]);
    }

    public function store(StoreRequest $request)
    {
        $caseMutator = new CaseMutator();
        $caseMutator->store($request->all());

        return response()->json_created();
    }

    public function update(UpdateRequest $request, $id)
    {
        $caseMutator = new CaseMutator();
        $caseMutator->update($id, $request->all());

        return response()->json_updated();
    }
}
