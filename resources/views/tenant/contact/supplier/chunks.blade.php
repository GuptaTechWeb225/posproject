@extends('layout.tenant')
@section('title', __t('export_lists'))

@section('contents')
    <app-supplier-export-lists :sheet-lists="{{ $customer_per_sheet }}" :per-sheet="{{$export_count}}"></app-supplier-export-lists>
@endsection
