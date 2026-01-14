@extends('layout.tenant')
@section('title', __t('stock overview'))

@section('contents')
    <app-stock-overview :variant_id="{{ $variant_id }}"></app-stock-overview>
@endsection