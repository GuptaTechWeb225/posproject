@extends('layout.tenant')
@section('title', __t('export_sales_return_report'))

@section('contents')
    <app-data-export
            :per-sheet="{{ $item_per_sheet }}"
            :total-sheet="{{$total_sheet_number}}"
            url="app/export/sales/return"
            title="{{__t('download_sales_return_data')}}"
    ></app-data-export>
@endsection
