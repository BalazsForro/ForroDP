<div
    class="modal fade"
    id="tokenModal"
    tabindex="-1"
    aria-labelledby="tokenModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tokenModalLabel">
                    Device Token Created
                </h5>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    <strong>Important:</strong><br>
                    This token will only be shown once.
                    Please copy and store it securely.
                </div>

                <div class="input-group mb-3">
                    <input
                        type="text"
                        id="token"
                        class="form-control"
                        readonly
                    >
                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        id="copyTokenBtn"
                    >
                        Copy
                    </button>
                </div>

            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-dismiss="modal"
                >
                    I have saved the token
                </button>
            </div>

        </div>
    </div>
</div>
