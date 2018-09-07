<?php

namespace App\Services\Mutators;


use App\Models\Order;

class OrderMutator implements MutatorContract
{

    public function store($data = [])
    {
        $order = Order::create($data);

        return $order;
    }

    public function update($id, $data = [])
    {
        $order = Order::findOrFail($id);
        $order->update($data);

        return $order;
    }

    public function delete($id)
    {
        $order = Order::find($id);
        $order->delete();
    }
}