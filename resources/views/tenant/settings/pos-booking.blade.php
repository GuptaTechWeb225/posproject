@extends('layout.tenant')
@section('title', __t('pos_settings'))

@push('before-styles')
    {{ style('vendor/summernote/summernote-lite.css') }}
@endpush

@section('contents')
    <app-tenant-pos-booking-settings-layout></app-tenant-pos-booking-settings-layout>
@endsection
