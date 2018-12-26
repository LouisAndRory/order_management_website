<?php

namespace App\Services\Features;


use App\Models\Order;

class OrderService
{
    public function getTotalFeeAttribute(Order $order)
    {
        $totalFee = 0;
        foreach ($order->cases as $case) {
            if ($case->price && $case->amount) {
                $totalFee += ($case->price * $case->amount);
            }
        }
        $totalFee += ($order->extra_fee - $order->deposit);

        $order->setAttribute('total_fee', abs($totalFee));
    }
}