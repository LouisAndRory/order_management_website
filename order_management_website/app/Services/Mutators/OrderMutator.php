<?php

namespace App\Services\Mutators;


use App\Models\Order;
use DB;

class OrderMutator implements MutatorContract
{

    /**
     * @param array $data
     * @return Order|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function store($data = [])
    {
        try {
            DB::beginTransaction();

            $order = Order::create($data);

            info("Order created", $order->toArray());

            if (array_has($data, 'cases')) {
                $caseData = array_get($data, 'cases', []);
                if (!empty($caseData)) {
                    $caseMutator = new CaseMutator();
                    foreach ($caseData as $caseDatum) {
                        $case = $caseMutator->store($caseDatum);
                        $order->cases()->save($case);

                        if (array_has($caseDatum, 'cookies')) {
                            $cookieData = array_get($caseDatum, 'cookies');
                            if (!empty($cookieData)) {
                                foreach ($cookieData as $cookieDatum) {
                                    $case->cookies()->attach($case->id, $cookieDatum);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $id
     * @param array $data
     * @return Order|Order[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function update($id, $data = [])
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $order->update($data);

            DB::commit();

            return $order;
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
        Order::find($id)->delete();
    }
}