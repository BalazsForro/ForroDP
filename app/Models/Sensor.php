<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sensor extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'device_id',
        'name',
        'key',
        'description',
        'display_sort_order',
        'required',
        'unit_type',
        'data_type',
        'min_value',
        'max_value',
    ];

    protected $casts = [
        'required' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class,'device_id');
    }
}
