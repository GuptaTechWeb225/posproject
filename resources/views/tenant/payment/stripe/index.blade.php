@extends('layout.tenant')
@section('title', __t('stripe_button'))

@section('contents')
    <app-stripe-layout intent="{{$intent}}" public-key="{{$public_key}}"></app-stripe-layout>
@endsection
