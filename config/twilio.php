<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | If you're using API credentials, change these settings. Get your
    | credentials from https://www.twilio.com | 'Settings'.
    |
    */

    'api_sid' => function_exists('env') ? env('TWILIO_SID', '') : '',
    'api_token' => function_exists('env') ? env('TWILIO_TOKEN', '') : '',
    'from_number' => function_exists('env') ? env('TWILIO_FROM', '') : '',


];
