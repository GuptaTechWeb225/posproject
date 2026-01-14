@extends('layout.auth')

@section('title', trans('default.login'))

@section('contents')
    @php
        ['logo' => $logo] = \App\Http\Composer\Helper\LogoIcon::new(true)->logoIcon();
    @endphp

    <app-auth-login
            logo-url="{{ $logo }}"
            app-name="{{ settings('company_name') }}"
            market-place-version="{{env('MARKET_PLACE_VERSION')}}">
    </app-auth-login>

@endsection

