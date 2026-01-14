@extends('layout.auth')

@section('title', trans('default.reset_password'))

@section('contents')
    @php
        ['logo' => $logo] = \App\Http\Composer\Helper\LogoIcon::new(true)->logoIcon();
    @endphp
    <app-password-reset logo-url="{{ $logo }}"
                        app-name="{{ settings('company_name') }}"></app-password-reset>
@endsection