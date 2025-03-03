<?php

namespace App\Repositories\Payment;

use App\Models\Payment;

class PaymentRepository implements PaymentInterface
{
    protected Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }


}

