<?php
return [
    /* Application version */
    'app_version' => '2',

    /* Application update url */
    'update_url' => 'https://marketplace.gainhq.com',

    /* Application id */
    'app_id' => 'salex',

    /*Determine if the app is Installed or not*/
    'installed' => env('APP_INSTALLED', true),

    /*Marketplace update route*/
    'use_update_route' => 'v2',
];
