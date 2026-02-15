<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceLatestState extends Model
{
    protected $primaryKey = 'device_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'measurement_id'
    ];

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class);
    }
}
