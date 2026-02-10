<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    protected $fillable = [
        'owner_user_id',
        'name',
        'description',
        'is_active',
        'type',
    ];

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * @return HasMany
     */
    public function shares(): HasMany
    {
        return $this->hasMany(DeviceShare::class, 'device_id');
    }

    /**
     * @return BelongsToMany
     */
    public function sharedUsers(): belongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'device_shares',
            'device_id',
            'shared_with_user_id'
        )->as('share')
            ->withPivot([
                'permission',
                'status',
                'accepted_at',
            ])->withTimestamps();
    }

    /**
     * @return HasOne
     */
    public function latestState(): HasOne
    {
        return $this->hasOne(DeviceLatestState::class, 'device_id');
    }

    public function token(): HasOne
    {
        return $this->hasOne(DeviceToken::class);
    }

    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class);
    }
}
