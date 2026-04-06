<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CodeSnippet extends Model
{
    protected $fillable = ['name', 'content'];

    public function deviceType(): HasOne
    {
        return $this->hasOne(DeviceType::class);
    }
}