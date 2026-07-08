<div class="lp-notes-card">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h5 class="mb-1">
                Company Notes
            </h5>

            <small class="text-muted">
                Meeting notes, follow-ups and reminders
            </small>

        </div>

    </div>

    <form action="{{ route('companies.notes.store', $company->uuid) }}"
          method="POST">

        @csrf

        <div class="mb-3">

            <textarea
                name="note"
                rows="4"
                class="form-control"
                placeholder="Write a meeting note, follow-up, reminder, discussion, or any important information..."></textarea>

        </div>

        <button class="btn btn-primary">

            <i class="fa-solid fa-plus me-1"></i>

            Add Note

        </button>

    </form>

    <hr class="my-4">

    @forelse($notes as $note)

        <div class="lp-note-item">

            <div class="d-flex justify-content-between">

                <strong>

                    {{ $note->created_at->format('d M Y H:i') }}

                </strong>

            </div>

            <p class="mt-2 mb-2">

                {{ $note->note }}

            </p>

            <small class="text-muted">

                {{ $note->created_at->diffForHumans() }}

            </small>

        </div>

    @empty

        <div class="text-center py-5">

            <i class="fa-regular fa-note-sticky fa-3x text-muted mb-3"></i>

            <h6>No Notes Yet</h6>

            <p class="text-muted">

                Add your first note above.

            </p>

        </div>

    @endforelse

</div>