<div>
    <div class="row bg-light bg-gradient rounded-1 py-2 shadow-sm border border-top-0 border-secondary-subtle">
        <div class="col-1 d-flex align-items-center">
            <h4 class="m-0 p-0">Devices</h4>
        </div>

        <div class="col-3 d-flex justify-content-between gap-2">
            <input
                type="text"
                class="form-control w-100"
                placeholder="Search..."
                wire:model.live.debounce.400ms="search"
            >
        </div>

        <div class="col-1 offset-7 d-flex justify-content-end">
            <button
                class="btn btn-success"
                wire:click="$dispatch('open-device-create')"
            >
                Create
            </button>
        </div>
    </div>

    <div class="accordion mt-3" id="devicesAccordion">
        @forelse ($devices as $device)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $device->id }}" aria-expanded="false"
                            aria-controls="collapse-{{ $device->id }}">
                        <div class="d-flex justify-content-between w-100 me-3">
                            <div class="col-10">
                                <div class="row p-2 col-10">
                                <span><strong>{{ $device->name }}</strong>
                                @isAdmin()
                                    <br>
                                    <span><b>Name</b>: {{ $device->owner->name }}, <b>Email</b>: {{ $device->owner->email }}</span>
                                @endisAdmin
                                </span>

                                </div>
                                <div class="row p-2 col-3">
                                    <small class="text-muted d-flex justify-content-between">
                                        {{ \App\Enums\DeviceType::from($device->type)->getDisplayName() }}
                                        @isOwner($device)
                                        <span class="badge bg-success">Owner</span>
                                        @else
                                            <span class="badge bg-info">Shared</span>
                                            @endisOwner
                                    </small>
                                </div>
                            </div>
                            <span class="d-grid gap-2">
                                @if($device->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                                <span class="badge border text-dark bg-light">{{ $device->token?->prefix ?? ' ' }}</span>
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="collapse-{{ $device->id }}" class="accordion-collapse collapse"
                     data-bs-parent="#devicesAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p><strong>Description:</strong> {{ $device->description }}</p>
                                <div>
                                    <strong>Sensors:</strong>
                                    @foreach($device->sensors as $sensor)
                                        <span class="badge border text-dark bg-light"
                                              title="{{ $sensor->description }}">
                                            {{ $sensor->name }}
                                            @if($sensor->required)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 d-grid gap-1" style="grid-template-columns: 1fr 1fr;">
                                <button class="btn btn-sm btn-outline-primary w-100" title="Edit"
                                        wire:click="dispatch('open-measurement',{deviceId:  {{ $device->id }}})">
                                    <i class="bi bi-activity"></i> Measurement
                                </button>

                                @canWrite($device)
                                <button class="btn btn-sm btn-outline-primary w-100" title="Edit"
                                        wire:click="edit({{ $device->id }})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                @endcanWrite()

                                @isOwner($device)
                                <button class="btn btn-sm btn-outline-info w-100" title="Edit"
                                        wire:click="dispatch('open-share',{deviceId:  {{ $device->id }}})">
                                    <i class="bi bi-share"></i> Share device
                                </button>
                                @endisOwner()

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded shadow-sm border p-4 text-muted text-center">
                No devices found
            </div>
        @endforelse
    </div>

    <livewire:devices.create-modal/>
    <livewire:devices.share-modal/>
    <livewire:measurement.show/>
    @include('livewire.devices.show-token-modal')
</div>
