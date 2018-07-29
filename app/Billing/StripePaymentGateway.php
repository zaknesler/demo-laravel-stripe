<?php

namespace App\Billing;

use Stripe\Charge;
use App\Billing\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    public function charge($amount, $customer, $metadata = null)
    {
        $charge = Charge::create([
            'amount' => $amount,
            'currency' => 'usd',
            'customer' => $customer['id'],
            'receipt_email' => $customer['email'],
            'metadata' => $metadata,
        ]);

        return request()->user()->orders()->create([
            'charge_id' => $charge['id'],
            'charged_at' => $charge['created'],
            'charge_amount' => $amount,
            'card_last_four' => $charge['source']['last_four'],
        ]);
    }
}
