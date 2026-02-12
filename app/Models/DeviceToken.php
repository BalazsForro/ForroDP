<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class DeviceToken extends Model
{
    /**  */
    use hasFactory;
    protected $fillable = [
        'device_id',
        'prefix',
        'token_hash',
        'rate_limit',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * @return string
     */
    public static function makePlainToken(): string
    {
        return Str::random(40);
    }

    /**
     * @param string $plainToken
     *
     * @return string
     */
    public static function hashToken(string $plainToken): string
    {
        return hash('sha256', $plainToken);
    }

    public static function createPrefix(string $plainToken): string
    {
        return substr($plainToken, 0, 3) . '...' . substr($plainToken, -3) ;
    }

    /*$incomingToken = $request->bearerToken();

$token = DeviceToken::where(
    'token_hash',
    hash('sha256', $incomingToken)
)->first();*/

}
