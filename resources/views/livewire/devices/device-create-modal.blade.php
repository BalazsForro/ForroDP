<div>
    <div wire:ignore.self class="modal fade" id="deviceCreateModal"
         data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-hidden="true"
    >
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Create device</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" wire:model="deviceName" maxlength="45">
                        @error('deviceName')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" wire:model="deviceDescription" maxlength="255">
                        </textarea>
                        @error('deviceDescription')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Device type</label>
                        <select class="form-select">
                            @foreach (\App\Enums\DeviceType::cases() as $deviceType)
                                <option value="{{ $deviceType->value }}">{{ $deviceType->getDisplayName() }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{--<livewire:devices.sensors-editor :key="'sensors-editor-create'"/>--}}
                    @include('livewire.devices._sensors-editor')
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" class="btn btn-success" wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove>Create</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
