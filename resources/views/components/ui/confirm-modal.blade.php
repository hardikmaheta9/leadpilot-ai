<div
    class="modal fade"
    id="deleteConfirmModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content lp-modal lp-confirm-modal">

            <div class="modal-body text-center p-4">

                <div class="lp-confirm-icon">
                    <i class="fa-solid fa-trash"></i>
                </div>

                <h4>Delete this item?</h4>

                <p id="deleteConfirmMessage">
                    This action cannot be undone.
                </p>

                <div class="d-flex justify-content-center gap-2 mt-4">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Cancel
                    </button>

                    <button
                        type="button"
                        class="lp-btn lp-confirm-delete-btn"
                        id="confirmDeleteButton">

                        <i class="fa-solid fa-trash"></i>
                        Delete
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>