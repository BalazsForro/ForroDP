<?php

namespace App\Livewire\Devices;

use App\Models\DeviceShare;
use Livewire\Component;

use Livewire\Attributes\Validate;

class _share extends Component
{
    public array $shares = [];
    public function fetchShares(int $deviceId): void
    {
        $this->shares = DeviceShare::with(['sharedUser', 'sharedBy'])
            ->where('device_id', $deviceId)
            ->get()->toArray();
    }
}
