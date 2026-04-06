<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeviceType extends Model
{
    protected $fillable = ['name', 'icon'];

    public function codeSnippet(): HasOne
    {
        return $this->hasOne(CodeSnippet::class);
    }
}