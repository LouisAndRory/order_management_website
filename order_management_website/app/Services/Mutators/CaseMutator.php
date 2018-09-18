<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 2018/9/5
 * Time: 上午11:59
 */

namespace App\Services\Mutators;


use App\Models\CaseModel;
use DB;

class CaseMutator implements MutatorContract
{
    /**
     * @param array $data
     * @return CaseModel|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function store($data = [])
    {
        try {
            DB::beginTransaction();

            $case = CaseModel::create($data);
            info("CaseModel created", $case->toArray());

            DB::commit();

            return $case;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param $id
     * @param array $data
     * @return CaseModel|CaseModel[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function update($id, $data = [])
    {
        try {
            DB::beginTransaction();

            $case = CaseModel::findOrFail($id);
            $case->CaseModel($data);

            info("CaseModel updated", $case->toArray());

            DB::commit();

            return $case;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        CaseModel::findOrFail($id)->delete();
    }
}