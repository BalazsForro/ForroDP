<div>
    <div class="row bg-light bg-gradient rounded-1 py-2 shadow-sm border border-top-0 border-secondary-subtle">
        <div class="col-1 d-flex align-items-center">
            <h4 class="m-0 p-0">Devices</h4>
        </div>

        <div class="col-5">
            <input
                type="text"
                class="form-control w-50"
                placeholder="Search..."
                wire:model.live.debounce.400ms="search"
            >
        </div>

        <div class="col-1 offset-5 d-flex justify-content-end">
            <button
                class="btn btn-success"
                wire:click="$dispatch('open-device-create')"
            >
                Create
            </button>
        </div>
    </div>

    <div class="mt-3">
        @forelse ($devices as $device)
            <div class="card mb-2">
                <div class="card-body">
                    {{ $device->name }}
                    <div class="row px-3 py-2">
                        @foreach($device->sensors as $sensor)
                            {{ $sensor->id }}
                            {{ $sensor->name }}
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted text-center py-4">
                No devices found
            </div>
        @endforelse
    </div>

    <livewire:devices.device-create-modal />
</div>
