@extends('layout.tenant')
@section('title', __t('edit_lot'))

@section('contents')
    <app-lot-create-edit :lot-id="{{ $lot_id }}"></app-lot-create-edit>
@endsection