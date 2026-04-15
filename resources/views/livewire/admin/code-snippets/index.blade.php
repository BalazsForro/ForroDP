<div>
    <div class="row bg-light bg-gradient rounded-1 py-2 shadow-sm border border-top-0 border-secondary-subtle mb-3">
        <div class="col-2 d-flex align-items-center">
            <h4 class="m-0 p-0">Code Snippets</h4>
        </div>
        <div class="col-1 offset-9 d-flex justify-content-end">
            <button
                class="btn btn-success"
                wire:click="$dispatch('open-code-snippet-create')"
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
                        <th style="width: 200px;">Name</th>
                        <th style="width: 160px;">Device Type</th>
                        <th>Snippet Preview</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($snippets as $snippet)
                        <tr>
                            <td class="text-muted">{{ $snippet->id }}</td>
                            <td class="fw-semibold">{{ $snippet->name }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($snippet->deviceType?->icon)
                                        <i class="bi {{ $snippet->deviceType->icon }} fs-5 text-secondary"></i>
                                    @endif
                                    <span class="fw-semibold">{{ $snippet->deviceType?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td>
                                <code class="text-muted small">{{ Str::limit($snippet->content, 80) }}</code>
                            </td>
                            <td class="d-flex gap-1">
                                <button
                                    class="btn btn-sm btn-outline-primary"
                                    wire:click="$dispatch('open-code-snippet-edit', { snippetId: {{ $snippet->id }} })"
                                >
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    wire:click="deleteSnippet({{ $snippet->id }})"
                                    wire:confirm="'Are you sure you want to delete this snippet?'"
                                >
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No code snippets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <livewire:admin.code-snippets.edit-modal/>
</div>