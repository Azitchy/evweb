<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IotDevice extends Model
{
    protected $fillable = [
        'charging_station_id',
        'device_id',
        'device_name',
        'status',
        'firmware_version',
        'current_power_kw',
        'voltage',
        'current_amps',
        'temperature',
        'last_heartbeat_at',
    ];

    protected function casts(): array
    {
        return [
            'current_power_kw' => 'decimal:2',
            'voltage' => 'decimal:2',
            'current_amps' => 'decimal:2',
            'temperature' => 'decimal:2',
            'last_heartbeat_at' => 'datetime',
        ];
    }

    public function chargingStation(): BelongsTo
    {
        return $this->belongsTo(ChargingStation::class);
    }

    public function telemetry(): HasMany
    {
        return $this->hasMany(IotTelemetry::class);
    }
}
