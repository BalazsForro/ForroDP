<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measurement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'device_id',
        'raw_payload',
        'is_valid',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(MeasurementValue::class);
    }
}
