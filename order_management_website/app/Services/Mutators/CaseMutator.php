<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 2018/9/5
 * Time: 上午11:59
 */

namespace App\Services\Mutators;


use App\Models\CaseModel;
use App\Models\CaseType;
use DB;

class CaseMutator implements MutatorContract
{
    /**
     * @param array $data
     * @return CaseType|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function store($data = [])
    {
        try {
            DB::beginTransaction();

            $case = CaseType::create($data);
            info("CaseType created", $case->toArray());

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
     * @return CaseType|CaseType[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function update($id, $data = [])
    {
        try {
            DB::beginTransaction();

            $case = CaseType::findOrFail($id);
            $case->update($data);

            info("CaseType updated", $case->toArray());

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
        CaseType::findOrFail($id)->delete();
    }
}