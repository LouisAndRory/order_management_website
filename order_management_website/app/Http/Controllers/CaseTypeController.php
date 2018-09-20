<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseType\StoreRequest;
use App\Http\Requests\CaseType\UpdateRequest;
use App\Models\CaseType;
use App\Services\Mutators\CaseTypeMutator;
use Illuminate\Http\Request;

class CaseTypeController extends Controller
{
    public function index()
    {
        $caseTypes = CaseType::select([
            'id', 'name', 'slug', 'enabled'
        ])->get();

        return view('management.cases',[
            'cases' => $caseTypes
        ]);
    }

    public function store(StoreRequest $request)
    {
        $caseTypeMutator = new CaseTypeMutator();
        try {
            $caseTypeMutator->store($request->all());
            return response()->json_created();
        } catch (\Exception $e) {
            return response()->json_create_failed();
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        $caseTypeMutator = new CaseTypeMutator();
        try {
            $caseTypeMutator->update($id, $request->all());
            return response()->json_updated();
        } catch (\Exception $e) {
            return response()->json_update_failed();
        }
    }

    public function delete($id)
    {
        $caseTypeMutator = new CaseTypeMutator();
        try {
            $caseTypeMutator->delete($id);
            return response()->json_deleted();
        } catch (\Exception $e) {
            return response()->json_delete_failed();
        }
    }
}
