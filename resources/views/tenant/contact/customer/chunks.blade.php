@extends('layout.tenant')
@section('title', __t('export_lists'))

@section('contents')
    <app-customer-export-lists :sheet-lists="{{ $customer_per_sheet }}" :per-sheet="{{$export_count}}"></app-customer-export-lists>
@endsection
