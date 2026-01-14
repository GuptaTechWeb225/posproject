@extends('layout.tenant')
@section('title', __t('product_details'))

@section('contents')
    <product-details :product-id="{{ $product_id }}"></product-details>
@endsection
