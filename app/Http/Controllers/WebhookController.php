<?php

namespace App\Http\Controllers;

class WebhookController extends Controller
{
    private $payload;

    public function __invoke()
    {
        $this->payload = collect(request()->all());

        if (method_exists($this, $this->eventToMethod())) {
            $this->{$this->eventToMethod()}();
        }
    }

    private function onChargeSucceeded()
    {
        die($this->payload->toJson());
    }

    private function eventToMethod()
    {
        return 'on' . studly_case(str_replace('.', '_', $this->payload->get('type')));
    }
}
