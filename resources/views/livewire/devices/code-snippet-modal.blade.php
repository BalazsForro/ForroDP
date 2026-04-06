<div>
    <div wire:ignore.self class="modal fade" id="codeSnippetModal"
         data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-hidden="true"
    >
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Code Snippet
                        @if($deviceName)
                            &mdash; <span class="text-muted fw-normal">{{ $deviceName }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    @if($snippetContent)
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading mb-1">What is this?</h6>
                            <p class="mb-1">
                                This is a ready-to-use code snippet that sends your sensor data
                                to this platform using an HTTP POST request.
                            </p>
                            <hr class="my-2">
                            <small>
                                <strong>How to use:</strong>
                                Copy the snippet below, fill in your credentials and bearer token,
                                update the sensor variable values, then run it on your device.
                            </small>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <h6 class="mb-0">Code Snippet</h6>
                                    <small class="text-muted">Replace the placeholders marked with <code>YOUR_</code>.</small>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary"
                                        onclick="copyCode('snippet-full')">
                                    Copy
                                </button>
                            </div>
                            <pre id="snippet-full" class="rounded mb-0" style="font-size: 0.85rem;"><code>{{ $snippetContent }}</code></pre>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            No code snippet available for this device type.
                        </div>
                    @endif

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('codeSnippetModal').addEventListener('shown.bs.modal', function () {
            document.querySelectorAll('#codeSnippetModal pre code').forEach(function (el) {
                if (!el.dataset.highlighted) {
                    hljs.highlightElement(el);
                }
            });
        });

        function copyCode(preId) {
            const pre = document.getElementById(preId);
            const text = (pre.querySelector('code') ?? pre).textContent;
            navigator.clipboard.writeText(text).then(() => {
                const btn = pre.previousElementSibling.querySelector('button');
                const original = btn.textContent;
                btn.textContent = 'Copied!';
                btn.classList.replace('btn-outline-secondary', 'btn-success');
                setTimeout(() => {
                    btn.textContent = original;
                    btn.classList.replace('btn-success', 'btn-outline-secondary');
                }, 2000);
            });
        }
    </script>
</div>