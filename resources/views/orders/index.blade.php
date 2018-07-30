@extends('layouts/base')

@section('title', 'Home')

@section('show-header', true)

@section('content')
    <div class="w-full mb-6">
        <div class="border rounded">
            <div class="border-b bg-grey-lightest rounded-t text-grey-darker font-semibold px-4 py-3">Your orders</div>

            <div class="bg-white rounded-b p-4">
                <div class="mb-4">
                    @foreach($orders as $order)
                        <a class="max-w-sm w-full border hover:border-grey rounded text-grey-darker bg-grey-lightest px-4 py-3 mb-4 flex items-center justify-between no-underline" href="{{ route('orders.show', $order) }}">
                            <div>
                                <div>{{ $order->charge_id }}</div>
                                <div class="text-sm text-grey-dark">{{ $order->created_at->setTimezone('America/New_York')->format('F j, Y g:i a') }}</div>
                            </div>

                            <div class="ml-4 font-mono text-grey-dark">${{ number_format($order->charge_amount / 100, 2) }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
