<?php

namespace App\Livewire\Devices;

use App\Models\Device;
use App\Models\DeviceShare;
use App\Models\User;
use Livewire\Attributes\Validate;

class ShareModal extends _share
{
    #[Validate('required')]
    public string $newShareEmail = '';

    #[Validate('required|integer|in:1,2')]
    public int $newSharePermission = 1;

    public int $deviceId;
    public array $userSuggestions = [];
    public bool $showSuggestions = false;

    protected $listeners = [
        'open-share' => 'open',
    ];

    protected array $messages = [
        'newShareEmail.exists' => 'No user found with this email address.',
    ];

    public function render()
    {
        return view('livewire.devices.share-modal');
    }

    public function open(int $deviceId): void
    {
        $this->deviceId = $deviceId;

        $this->fetchShares($deviceId);
        $this->dispatch('bs-modal-open', id: 'shareModal');
    }

    public function updatedNewShareEmail(string $value): void
    {
        $value = trim($value);

        if (mb_strlen($value) < 3) {
            $this->resetSuggestions();
            return;
        }

        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->where('email', 'like', "%{$value}%")
            ->orWhere('name', 'like', "%{$value}%")
            ->orderBy('name')
            ->limit(5)
            ->get();

        $this->userSuggestions = $users->map(fn($u) => [
            'id'    => $u->id,
            'name'  => $u->name,
            'email' => $u->email,
        ])->all();

        $this->showSuggestions = count($this->userSuggestions) > 0;
    }

    public function selectSuggestedUser(int $userId): void
    {
        $u = collect($this->userSuggestions)->firstWhere('id', $userId);

        if (!$u) {
            return;
        }

        $this->newShareEmail = $u['email'];
        $this->resetSuggestions();
    }

    public function resetSuggestions(): void
    {
        $this->userSuggestions = [];
        $this->showSuggestions = false;
    }

    public function addUser(): void
    {
        $this->validate($this->rules());

        $user = User::where('email', $this->newShareEmail)->first();

        if (!$user) {
            return;
        }

        DeviceShare::create([
            'device_id'           => $this->deviceId,
            'shared_with_user_id' => $user->id,
            'shared_by_user_id'   => auth()->id(),
            'permission'          => $this->newSharePermission,
            'accepted_at'         => now(),
        ]);

        $this->fetchShares($this->deviceId);
    }

    protected function rules(): array
    {
        return [
            'newShareEmail'      => [
                'required',
                'exists:users,email',
                function ($attr, $value, $fail) {
                    $userId = User::where('email', $value)->value('id');
                    if (!$userId) {
                        return;
                    }

                    $ownerId = Device::whereKey($this->deviceId)->value('owner_user_id');
                    if ($ownerId && (int)$ownerId === (int)$userId) {
                        $fail('You cannot share the device with the owner.');
                    }

                    $already = DeviceShare::where('device_id', $this->deviceId)
                        ->where('shared_with_user_id', $userId)
                        ->exists();

                    if ($already) {
                        $fail('This user is already in the share list.');
                    }
                },
            ],
            'newSharePermission' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function removeShare(int $shareId)
    {
        Device::find($this->deviceId)->shares()->where('id', $shareId)->delete();
        $this->fetchShares($this->deviceId);
    }

    public function updateShare(int $shareId, int $index)
    {
        $share = DeviceShare::find($shareId);
        if (!$share) {
            return;
        }

        $share->permission = $this->shares[$index]['permission'];
        $share->save();

        $this->dispatch('bs-toast-show', message: 'Share updated successfully.');
    }
}
