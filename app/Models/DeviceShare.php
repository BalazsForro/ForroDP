<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceShare extends Model
{
    use SoftDeletes;

    public const PERMISSION_NONE = 0;
    public const PERMISSION_READ = 1;
    public const PERMISSION_READ_WRITE = 2;

    protected $fillable = [
        'device_id',
        'shared_with_user_id',
        'shared_by_user_id',
        'permission',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    /**
     * @return BelongsTo
     */
    public function sharedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }

    /**
     * @return BelongsTo
     */
    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by_user_id');
    }

    /**
     * @return bool
     */
    public function canRead(): bool
    {
        return $this->permission >= self::PERMISSION_READ;
    }

    /**
     * @return bool
     */
    public function canWrite(): bool
    {
        return $this->permission >= self::PERMISSION_READ_WRITE;
    }
}
