@extends('layouts/base')

@section('title', 'Home')

@section('show-header', true)

@section('content')
    <div class="w-full mb-6">
        <div class="border rounded">
            <div class="border-b bg-grey-lightest rounded-t text-grey-darker font-semibold px-4 py-3">Order {{ $order->id }} ({{ $order->charge_id }})</div>

            <div class="bg-white rounded-b p-4">
                <div class="mb-4">
                    @foreach($order->products as $product)
                        <div class="max-w-xs w-full border rounded bg-grey-lightest px-4 py-3 mb-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="font-semibold">1</div>

                                <svg class="mx-2 w-2 h-2 text-grey-darker fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/></svg>

                                <div class="text-left">{{ $product->name }}</div>
                            </div>

                            <div class="ml-4 font-mono text-grey-dark">${{ number_format($product->price / 100, 2) }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="text-grey-darkest text-lg font-semibold">${{ number_format($order->charge_amount / 100, 2) }}</div>
            </div>
        </div>
    </div>
@endsection
