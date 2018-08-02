@extends('layouts/base')

@section('title', 'Cart')

@section('show-header', true)

@section('content')
    <checkout :items="{{ $items }}"></checkout>
@endsection
