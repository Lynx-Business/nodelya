<?php

namespace App\Services;

class ConversionService
{
    public function __construct(
        //
    ) {}

    /**
     * Convert cents to readable price.
     */
    public static function centsToPrice(?int $value = 0): float
    {
        $value ??= 0;

        return round($value / 100, 2);
    }

    /**
     * Convert price to cents.
     */
    public static function priceToCents(?float $value = 0): int
    {
        $value ??= 0;

        return (int) round($value * 100);
    }
}
