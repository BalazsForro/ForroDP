<div class="border rounded p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="fw-semibold">Sensors</div>

        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addSensor">
            <i class="bi bi-plugin"></i>
            Add sensor
        </button>
    </div>

    @if (count($sensors) === 0)
        <div class="text-muted small">
            No sensors yet. Click “Add sensor”.
        </div>
    @endif

    <div class="vstack gap-2">
        @foreach ($sensors as $i => $sensor)
            <div class="card">
                <div class="card-body">
                    {{-- Row 1: Name + Key + Remove --}}
                    <div class="row g-2 align-items-end">
                        <div class="col-6">
                            <label class="form-label mb-1">Name</label>
                            <input type="text"
                                   class="form-control @error('sensors.'.$i.'.name') is-invalid @enderror"
                                   wire:model.live.debounce.250ms="sensors.{{ $i }}.name"
                                   maxlength="45">
                            @error('sensors.'.$i.'.name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-4">
                            <label class="form-label mb-1">
                                Key
                                <small
                                    class="text-muted ms-1"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    data-bs-title="This key is automatically generated from the sensor name and is used by the system when receiving data."
                                >
                                    <i class="bi bi-question-circle"></i>
                                </small>
                            </label>
                            <input type="text" disabled
                                   class="form-control @error('sensors.'.$i.'.key') is-invalid @enderror"
                                   wire:model.live.debounce.250ms="sensors.{{ $i }}.key"
                                   maxlength="45">
                            @error('sensors.'.$i.'.key')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-2 text-end">
                            <button type="button"
                                    class="btn btn-outline-danger"
                                    wire:click="removeSensor({{ $i }})"
                                    title="Remove sensor">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Row 2: Description --}}
                    <div class="row g-2 mt-2">
                        <div class="col-12">
                            <label class="form-label mb-1">Description</label>
                            <textarea class="form-control @error('sensors.'.$i.'.description') is-invalid @enderror"
                                      rows="2"
                                      wire:model.live.debounce.250ms="sensors.{{ $i }}.description"
                                      maxlength="255"></textarea>
                            @error('sensors.'.$i.'.description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 3: Unit + Data type + Sort + Required --}}
                    <div class="row g-2 mt-2 align-items-end">
                        <div class="col-4">
                            <label class="form-label mb-1">Unit type</label>
                            <input type="text"
                                   class="form-control @error('sensors.'.$i.'.unit_type') is-invalid @enderror"
                                   wire:model.live.debounce.250ms="sensors.{{ $i }}.unit_type"
                                   maxlength="50"
                                   placeholder="°C, %, V, ...">
                            @error('sensors.'.$i.'.unit_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-3">
                            <label class="form-label mb-1">Data type</label>
                            <select class="form-select @error('sensors.'.$i.'.data_type') is-invalid @enderror"
                                    wire:model.live="sensors.{{ $i }}.data_type">
                                @foreach (\App\Enums\DataType::cases() as $type)
                                    <option value="{{ $type->value }}">{{ $type->getDisplayName() }}</option>
                                @endforeach
                            </select>
                            @error('sensors.'.$i.'.data_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @dump($errors)
                            @enderror
                        </div>

                        <div class="col-2">
                            <label class="form-label mb-1">
                                Sort order
                                <small
                                    class="text-muted ms-1"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    data-bs-title="Controls the display order of sensor values in the user interface. Lower numbers appear first."
                                >
                                    <i class="bi bi-question-circle"></i>
                                </small>
                            </label>
                            <input type="number"
                                   class="form-control @error('sensors.'.$i.'.display_sort_order') is-invalid @enderror"
                                   wire:model.live="sensors.{{ $i }}.display_sort_order"
                                   min="0"
                                   step="1">
                            @error('sensors.'.$i.'.display_sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-3 d-flex align-items-center justify-content-center">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       wire:model.live="sensors.{{ $i }}.required"
                                       id="sensorRequired{{ $i }}">

                                <label class="form-check-label ms-1" for="sensorRequired{{ $i }}">
                                    Required
                                    <small
                                        class="text-muted ms-1"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        data-bs-title="If enabled, the system will reject incoming requests that do not contain a value for this sensor."
                                    >
                                        <i class="bi bi-question-circle"></i>
                                    </small>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Row 4: Min/Max --}}
                    <div class="row g-2 mt-2">
                        <div class="col-3">
                            <label class="form-label mb-1">Min value</label>
                            <input type="number"
                                   class="form-control @error('sensors.'.$i.'.min_value') is-invalid @enderror"
                                   wire:model.live="sensors.{{ $i }}.min_value"
                                   step="any">
                            @error('sensors.'.$i.'.min_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-3">
                            <label class="form-label mb-1">Max value</label>
                            <input type="number"
                                   class="form-control @error('sensors.'.$i.'.max_value') is-invalid @enderror"
                                   wire:model.live="sensors.{{ $i }}.max_value"
                                   step="any">
                            @error('sensors.'.$i.'.max_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 d-flex align-items-end">
                            <div class="text-muted small">
                                Tip: if you set min/max, the system can validate incoming values.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>
