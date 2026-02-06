<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeasurementValue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'measurement_id',
        'sensor_id',
        'value',
    ];

    /**
     * @return BelongsTo
     */
    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class);
    }

    /**
     * @return BelongsTo
     */
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }
}
