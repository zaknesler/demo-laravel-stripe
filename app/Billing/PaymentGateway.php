<?php

namespace App\Billing;

interface PaymentGateway
{
    public function charge($amount, $source, $customer);
}
