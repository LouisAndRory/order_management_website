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

        return view('management.cases',[
            'cases' => $cases
        ]);
    }

    public function store(StoreRequest $request)
    {
        $caseMutator = new CaseMutator();
        try {
            $caseMutator->store($request->all());
            return response()->json_created();
        } catch (\Exception $e) {
            return response()->json_create_failed();
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        $caseMutator = new CaseMutator();
        try {
            $caseMutator->update($id, $request->all());
            return response()->json_updated();
        } catch (\Exception $e) {
            return response()->json_update_failed();
        }
    }
}
