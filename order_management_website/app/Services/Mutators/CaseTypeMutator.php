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
        $caseType = CaseType::findOrFail($id);
        if ($caseType->deletable) {
            $caseType->delete();

            info("CaseType $id deleted");
        } else {
            throw new \Exception("CaseType $id can't be deleted");
        }
    }
}