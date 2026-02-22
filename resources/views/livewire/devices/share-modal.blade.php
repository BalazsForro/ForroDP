<div>
    <div wire:ignore.self class="modal fade" id="shareModal"
         data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-hidden="true"
    >
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Share device</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    {{-- Add new share --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="mb-3">Add user</h6>

                            <div class="row g-2 align-items-end">
                                <div class="col-12 col-md-7">
                                    <label class="form-label mb-1">User email</label>
                                    <div class="position-relative">
                                        <input
                                            type="email"
                                            class="form-control @error('newShareEmail') is-invalid @enderror"
                                            placeholder="user@example.com"
                                            wire:model.live.debounce.300ms="newShareEmail"
                                            wire:keydown.escape="resetSuggestions"
                                        >
                                        @error('newShareEmail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        @if($showSuggestions)
                                            <div
                                                class="list-group position-absolute w-100 shadow"
                                                style="z-index: 1056; max-height: 240px; overflow:auto;"
                                            >
                                                @foreach($userSuggestions as $u)
                                                    <button
                                                        type="button"
                                                        class="list-group-item list-group-item-action"
                                                        wire:click="selectSuggestedUser({{ $u['id'] }})"
                                                    >
                                                        <div class="fw-semibold">{{ $u['name'] ?? $u['email'] }}</div>
                                                        <div class="small text-muted">{{ $u['email'] }}</div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    @error('newShareEmail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-3">
                                    <label class="form-label mb-1">Permission</label>
                                    <select
                                        class="form-select @error('newSharePermission') is-invalid @enderror"
                                        wire:model.defer="newSharePermission"
                                    >
                                        <option value="{{ \App\Models\DeviceShare::PERMISSION_READ }}">Read</option>
                                        <option value="{{ \App\Models\DeviceShare::PERMISSION_READ_WRITE }}">Read &
                                            Write
                                        </option>
                                    </select>
                                    @error('newSharePermission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-2 d-grid">
                                    <button
                                        type="button"
                                        class="btn btn-success"
                                        wire:click="addUser"
                                        wire:loading.attr="disabled"
                                        wire:target="addUser"
                                    >
                                        <span wire:loading.remove wire:target="addUser">Add</span>
                                        <span wire:loading wire:target="addUser">Adding...</span>
                                    </button>
                                </div>
                            </div>

                            <div class="form-text mt-2">
                                Add by email. If the user exists, they’ll be added to the share list.
                            </div>
                        </div>
                    </div>

                    {{-- Shared users list --}}
                    <h6 class="mb-2">Shared users</h6>

                    @if(empty($shares) || count($shares) === 0)
                        <div class="alert alert-light border mb-0">
                            No one has access to this device yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                <tr>
                                    <th>User</th>
                                    <th style="width: 220px;">Permission</th>
                                    <th style="width: 140px;">Status</th>
                                    <th>Shared by</th>
                                    <th style="width: 90px;" class="text-end"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shares as $i => $share)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $share['shared_user']['name'] ?? '—' }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $share['shared_user']['email'] ?? 'Unknown user' }}
                                            </div>
                                        </td>

                                        <td>
                                            <select
                                                class="form-select form-select-sm"
                                                wire:model="shares.{{ $i }}.permission"
                                                wire:change="updateShare({{ $share['id'] }}, {{ $i }})"
                                            >
                                                <option value="{{ \App\Models\DeviceShare::PERMISSION_NONE }}">None
                                                </option>
                                                <option value="{{ \App\Models\DeviceShare::PERMISSION_READ }}">Read
                                                </option>
                                                <option value="{{ \App\Models\DeviceShare::PERMISSION_READ_WRITE }}">
                                                    Read & Write
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            @if(!empty($share['accepted_at']))
                                                <span class="badge text-bg-success">Accepted</span>
                                                <div class="text-muted small">
                                                    {{ \Illuminate\Support\Carbon::parse($share['accepted_at'])->diffForHumans() }}
                                                </div>
                                            @else
                                                <span class="badge text-bg-warning">Pending</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="text-muted small">
                                                {{ $share['shared_by']['name'] ?? '—' }}
                                                @if(!empty($share['shared_by']['email']))
                                                    <span>({{ $share['shared_by']['email'] }})</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="text-end">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Remove"
                                                wire:click="removeShare({{ $share['id'] }})"
                                                wire:loading.attr="disabled"
                                                wire:target="removeShare({{ $share['id'] }})"
                                            >
                                                <span wire:loading.remove wire:target="removeShare({{ $share['id'] }})">
                                                    Remove
                                                </span>
                                                <span wire:loading wire:target="removeShare({{ $share['id'] }})">
                                                    ...
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
