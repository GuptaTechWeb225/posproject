@extends('layout.tenant')
@section('title', __t('edit_product'))

@section('contents')
    <app-product-crete-edit :product-id="{{ $product_id }}"></app-product-crete-edit>
@endsection