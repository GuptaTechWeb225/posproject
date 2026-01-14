@extends('layout.tenant')
@section('title', __t('export_lists'))

@section('contents')
    <app-data-export
            :per-sheet="{{ $item_per_sheet }}"
            :total-sheet="{{$total_sheet_number}}"
            url="app/export/categories"
            title="{{__t('download_category_data')}}"
    ></app-data-export>
@endsection
