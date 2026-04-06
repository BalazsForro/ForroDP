<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceType extends Model
{
    protected $fillable = ['name', 'icon', 'code_snippet_id'];

    public function codeSnippet(): BelongsTo
    {
        return $this->belongsTo(CodeSnippet::class);
    }
}