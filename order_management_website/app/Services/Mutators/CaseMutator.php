<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 2018/9/5
 * Time: 上午11:59
 */

namespace App\Services\Mutators;


use App\Models\CaseModel;

class CaseMutator implements MutatorContract
{
    public function store($data = [])
    {
        $case = CaseModel::create($data);

        return $case;
    }

    public function update($id, $data = [])
    {
        $case = CaseModel::findOrFail($id);
        $case->update($data);

        return $case;
    }

    public function delete($id)
    {

    }
}