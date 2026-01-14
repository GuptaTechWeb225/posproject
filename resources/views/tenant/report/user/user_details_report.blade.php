@extends('layout.tenant')
@section('title', __t('user_details_report'))

@section('contents')
    <app-user-report-details :user="{{ $user }}"></app-user-report-details>
@endsection
