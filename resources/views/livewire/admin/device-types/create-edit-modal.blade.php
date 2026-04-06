<div>
    <div wire:ignore.self class="modal fade" id="deviceTypeModal"
         data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ $deviceType ? 'Edit Device Type' : 'Create Device Type' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" wire:model="name" maxlength="45" placeholder="e.g. Arduino">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Icon
                            <span class="text-muted small">(Bootstrap Icons class, e.g. <code>bi-cpu</code>)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi {{ $icon ?: 'bi-question-circle' }}"></i>
                            </span>
                            <input type="text" class="form-control" wire:model.live="icon" maxlength="50" placeholder="bi-cpu">
                        </div>
                        @error('icon')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Code Snippet</label>
                        <select class="form-select" wire:model="codeSnippetId">
                            <option value="">— None —</option>
                            @foreach ($codeSnippets as $snippet)
                                <option value="{{ $snippet->id }}">{{ $snippet->name }}</option>
                            @endforeach
                        </select>
                        @error('codeSnippetId')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="button"
                        class="btn btn-success"
                        wire:click="{{ $deviceType ? 'update' : 'save' }}"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>{{ $deviceType ? 'Save' : 'Create' }}</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>