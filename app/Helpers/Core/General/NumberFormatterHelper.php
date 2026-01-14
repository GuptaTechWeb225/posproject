<?php

namespace App\Helpers\Core\General;

class NumberFormatterHelper
{
    public function numberWithCurrencySymbol($number): string
    {
        if ($this->getCurrencyPosition() == 'prefix_with_space') {
            return $this->getCurrencySymbol() . ' ' . $this->numberFormatter($number);
        } elseif ($this->getCurrencyPosition() == 'prefix_only') {
            return $this->getCurrencySymbol() . $this->numberFormatter($number);
        } elseif ($this->getCurrencyPosition() == 'suffix_with_space') {
            return $this->numberFormatter($number) . ' ' . $this->getCurrencySymbol();
        } else {
            return $this->numberFormatter($number) . $this->getCurrencySymbol();
        }
    }

    public function numberFormatter($number): string
    {
        $number = number_format($number, $this->getNumberOfDecimal());
        $parts = explode('.', $number);
        $parts[0] = preg_replace('/\B(?=(\d{3})+(?!\d))/', $this->getThousandSeparator(), $parts[0]);
        return implode($this->getDecimalSeparator(), $parts);
    }

    public function getNumberOfDecimal()
    {
        return settings('number_of_decimal');
    }

    public function getThousandSeparator()
    {
        return settings('thousand_separator');
    }

    public function getDecimalSeparator()
    {
        return settings('decimal_separator');
    }

    public function getCurrencySymbol()
    {
        return settings('currency_symbol');
    }

    public function getCurrencyPosition()
    {
        return settings('currency_position');
    }
}
