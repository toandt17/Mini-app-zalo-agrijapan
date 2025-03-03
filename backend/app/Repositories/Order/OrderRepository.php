<?php

namespace App\Repositories\Order;

use App\Models\Order;

class OrderRepository implements OrderInterface
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

}

