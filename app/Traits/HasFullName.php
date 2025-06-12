<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasFullName
{
    public static function bootHasFullName(): void {}

    public function initializeHasFullName(): void {}

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => ucwords($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => ucwords($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn (?string $value): string => $value ? ucwords($value) : "{$this->first_name} {$this->last_name}",
        );
    }
}
