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
        'value'
    ];

    protected $casts = [
        'value' => 'array'
    ];

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
