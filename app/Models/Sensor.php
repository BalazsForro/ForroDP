<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sensor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'device_id',
        'name',
        'key',
        'description',
        'display_sort_order',
        'is_required',
        'min_value',
        'max_value',
        'unit_type',
        'data_type',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
