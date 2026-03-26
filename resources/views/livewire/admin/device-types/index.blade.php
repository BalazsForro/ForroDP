<div>
    <div class="row bg-light bg-gradient rounded-1 py-2 shadow-sm border border-top-0 border-secondary-subtle mb-3">
        <div class="col-2 d-flex align-items-center">
            <h4 class="m-0 p-0">Device Types</h4>
        </div>
        <div class="col-1 offset-9 d-flex justify-content-end">
            <button
                class="btn btn-success"
                wire:click="$dispatch('open-device-type-create')"
            >
                Create
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 60px;">Icon</th>
                        <th>Name</th>
                        <th style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deviceTypes as $type)
                        <tr>
                            <td class="text-muted">{{ $type->id }}</td>
                            <td>
                                @if ($type->icon)
                                    <i class="bi {{ $type->icon }} fs-5"></i>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $type->name }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button
                                        class="btn btn-sm btn-outline-primary"
                                        wire:click="$dispatch('open-device-type-edit', { deviceTypeId: {{ $type->id }} })"
                                    >
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        wire:confirm="'Are you sure you want to delete this device type?'"
                                        wire:click="delete({{ $type->id }})"
                                    >
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No device types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <livewire:admin.device-types.create-edit-modal/>
</div>