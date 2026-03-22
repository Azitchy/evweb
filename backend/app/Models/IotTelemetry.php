<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IotTelemetry extends Model
{
    protected $table = 'iot_telemetry';

    protected $fillable = [
        'iot_device_id',
        'charging_session_id',
        'power_kw',
        'voltage',
        'current_amps',
        'temperature',
        'energy_kwh',
        'battery_percentage',
    ];

    protected function casts(): array
    {
        return [
            'power_kw' => 'decimal:2',
            'voltage' => 'decimal:2',
            'current_amps' => 'decimal:2',
            'temperature' => 'decimal:2',
            'energy_kwh' => 'decimal:3',
            'battery_percentage' => 'decimal:2',
        ];
    }

    public function iotDevice(): BelongsTo
    {
        return $this->belongsTo(IotDevice::class);
    }

    public function chargingSession(): BelongsTo
    {
        return $this->belongsTo(ChargingSession::class);
    }
}
