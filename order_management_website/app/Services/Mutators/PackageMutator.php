<?php

namespace App\Services\Mutators;


use App\Models\Package;
use DB;

class PackageMutator implements MutatorContract
{

    /**
     * @param array $data
     * @return Package|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function store($data = [])
    {
        try {
            DB::beginTransaction();

            $package = Package::create($data);

            if (array_has($data, 'cases')) {
                $caseData = array_get($data, 'cases', []);
                if (!empty($caseData)) {
                    foreach ($caseData as $caseDatum) {
                        $package->cases()->create($caseDatum);
                    }
                }
            }

            info("Package created", $package->toArray());

            DB::commit();

            return $package;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $id
     * @param array $data
     * @return Package|Package[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function update($id, $data = [])
    {
        try {
            DB::beginTransaction();

            $package = Package::findOrFail($id);
            $package->update($data);

            if (array_has($data, 'cases')) {
                $caseData = array_get($data, 'cases', []);
                if (!empty($caseData)) {
                    $package->cases()->delete();
                    foreach ($caseData as $caseDatum) {
                        $package->cases()->create($caseDatum);
                    }
                }
            }

            info("Package updated", $package->toArray());

            DB::commit();

            return $package;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        Package::find($id)->delete();
    }
}