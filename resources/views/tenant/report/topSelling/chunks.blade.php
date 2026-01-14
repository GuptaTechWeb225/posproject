@extends('layout.tenant')
@section('title', __t('export_top_selling_products'))

@section('contents')
    <app-data-export
            :per-sheet="{{ $item_per_sheet }}"
            :total-sheet="{{$total_sheet_number}}"
            url="app/export/top-selling-products"
            title="{{__t('download_sales_data')}}"
    ></app-data-export>
@endsection
