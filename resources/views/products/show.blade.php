@extends('layouts/base')

@section('title', 'Home')

@section('show-header', true)

@section('content')
    <div class="w-full mb-6">
        <div class="border rounded">
            <div class="border-b bg-grey-lightest rounded-t text-grey-darker font-semibold px-4 py-3">Product</div>

            <div class="bg-white rounded-b p-4">
                <div class="flex flex-col md:flex-row">
                    <img class="block w-48 h-48 rounded select-none pointer-events-none" src="{{ $product->image }}" alt="Product Image" />

                    <div class="mt-4 md:mt-0 md:ml-4">
                        <div class="text-grey-darkest text-xl font-semibold mb-2">{{ $product->name }}</div>

                        <div class="mb-4">{{ $product->description }}</div>

                        <div class="text-xl text-grey-darkest">${{ number_format($product->price / 100, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
        <purchase-product :product="{{ $product }}"></purchase-product>
    @endauth
@endsection
