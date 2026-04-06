<div>
    <div wire:ignore.self class="modal fade" id="codeSnippetEditModal"
         data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-hidden="true"
    >
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit Code Snippet
                        @if($snippet?->deviceType)
                            &mdash; <span class="text-muted fw-normal">{{ $snippet->deviceType->name }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading mb-1">Available Placeholders</h6>
                        <p class="mb-1 small">Use these tokens in your snippet — they are replaced automatically when shown to the user.</p>
                        <hr class="my-2">
                        <div class="d-flex flex-wrap gap-3 small">
                            <div>
                                <code>&#123;&#123;SERVER_URL&#125;&#125;</code>
                                <span class="text-muted ms-1">— the API endpoint URL</span>
                            </div>
                            <div>
                                <code>&#123;&#123;VARIABLES&#125;&#125;</code>
                                <span class="text-muted ms-1">— generated sensor variable declarations</span>
                            </div>
                            <div>
                                <code>&#123;&#123;JSON_BODY&#125;&#125;</code>
                                <span class="text-muted ms-1">— generated JSON body string</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Snippet Content</label>
                        <textarea
                            class="form-control @error('content') is-invalid @enderror"
                            wire:model="content"
                            rows="30"
                            style="font-family: monospace; font-size: 0.85rem; resize: vertical;"
                            placeholder="Paste your code snippet here..."
                            spellcheck="false"
                        ></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="button"
                        class="btn btn-success"
                        wire:click="save"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Save</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
