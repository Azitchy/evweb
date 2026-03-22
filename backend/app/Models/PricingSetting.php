<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingSetting extends Model
{
    protected $fillable = [
        'price_per_percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_per_percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public static function getCurrentPrice(): float
    {
        $setting = self::where('is_active', true)->latest()->first();
        return $setting ? (float) $setting->price_per_percentage : 0;
    }
}
