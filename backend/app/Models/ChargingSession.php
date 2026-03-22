<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChargingSession extends Model
{
    protected $fillable = [
        'user_id',
        'charging_station_id',
        'start_percentage',
        'end_percentage',
        'charged_percentage',
        'cost',
        'price_per_percentage',
        'status',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'start_percentage' => 'decimal:2',
            'end_percentage' => 'decimal:2',
            'charged_percentage' => 'decimal:2',
            'cost' => 'decimal:2',
            'price_per_percentage' => 'decimal:2',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chargingStation(): BelongsTo
    {
        return $this->belongsTo(ChargingStation::class);
    }
}
