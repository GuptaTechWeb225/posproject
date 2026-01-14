@extends('layout.tenant')
@section('title', ucwords(__t('customer_details')))

@section('contents')
    <app-customer-details :customer-id="{{ $customer_id }}"></app-customer-details>
@endsection
