<?php

use App\Helpers\Core\General\NumberFormatterHelper;

if (!function_exists('number_with_currency_symbol')) {
    function number_with_currency_symbol($number)
    {
        return resolve(NumberFormatterHelper::class)->numberWithCurrencySymbol($number);
    }
}

if (!function_exists('number_formatter')) {
    function number_formatter($number)
    {
        return resolve(NumberFormatterHelper::class)->numberFormatter($number);
    }
}
