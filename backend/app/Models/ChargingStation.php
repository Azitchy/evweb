<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChargingStation extends Model
{
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'status',
        'total_ports',
        'available_ports',
        'charger_type',
        'power_kw',
        'description',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'power_kw' => 'decimal:2',
        ];
    }

    public function chargingSessions(): HasMany
    {
        return $this->hasMany(ChargingSession::class);
    }

    public function iotDevices(): HasMany
    {
        return $this->hasMany(IotDevice::class);
    }
}
