@extends('layout.tenant')
@section('title', __t('export_user_report'))

@section('contents')
    <app-data-export
            :per-sheet="{{ $item_per_sheet }}"
            :total-sheet="{{$total_sheet_number}}"
            url="app/export/users"
            title="{{__t('download_sales_data')}}"
    ></app-data-export>
@endsection
