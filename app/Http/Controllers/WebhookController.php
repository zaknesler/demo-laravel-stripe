<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * The Webhook payload.
     *
     * @var \Illuminate\Support\Collection
     */
    private $payload;

    /**
     * Handle the Webhook request.
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function __invoke(Request $request)
    {
        try {
            $this->payload = collect(\Stripe\Webhook::constructEvent(
                $request->getContent(),
                $request->header('stripe-signature'),
                config('services.stripe.endpoint_secret')
            ));
        } catch (\UnexpectedValueException $e) {
            abort(400);
        } catch (\Stripe\Error\SignatureVerification $e) {
            abort(400);
        }

        if (method_exists($this, $this->eventToMethod())) {
            $this->{$this->eventToMethod()}();
        }
    }

    /**
     * Convert current payload event to appropriate method name.
     *
     * @return string
     */
    private function eventToMethod()
    {
        return 'on' . studly_case(str_replace('.', '_', $this->payload->get('type')));
    }

    /**
     * Handle the "charge.succeeded" event.
     *
     * @return void
     */
    private function onChargeSucceeded()
    {
        die($this->payload->toJson());
    }
}
