<?php

namespace App\Services\Mutators;


use App\Models\CaseType;

class CaseTypeMutator implements MutatorContract
{

    public function store($data = [])
    {
        $caseType = CaseType::create($data);
        info("CaseType created", $caseType->toArray());

        return $caseType;
    }

    public function update($id, $data = [])
    {
        $caseType = CaseType::findOrFail($id);
        $caseType->update($data);

        info("CaseType updated", $caseType->toArray());

        return $caseType;
    }

    public function delete($id)
    {

    }
}