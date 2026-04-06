<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodeSnippet extends Model
{
    protected $fillable = ['device_type_id', 'content'];

    public function deviceType(): BelongsTo
    {
        return $this->belongsTo(DeviceType::class);
    }
}