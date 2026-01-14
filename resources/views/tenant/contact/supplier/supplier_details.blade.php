@extends('layout.tenant')
@section('title', ucwords(__t('supplier_details')))

@section('contents')
    <app-supplier-details :supplier-id="{{ $supplierId }}"></app-supplier-details>
@endsection
