<div class="lp-module-card">

    <div class="lp-module-header">

        <div>
            <span class="lp-module-eyebrow">Knowledge</span>

            <h4>Company Notes</h4>

            <p>Store discussions, meeting summaries and important information.</p>
        </div>

        <button
            type="button"
            class="lp-btn lp-btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addNoteModal">

            <i class="fa-solid fa-note-sticky"></i>
            Add Note

        </button>

    </div>

    <div class="lp-module-body">

        <div class="row g-4">

            @forelse($notes as $note)

                <div class="col-lg-6">

                    <div class="lp-premium-note-card">

                        <div class="lp-note-top">

                            <div class="lp-note-date">

                                <div class="lp-note-icon">
                                    <i class="fa-solid fa-note-sticky"></i>
                                </div>

                                <div>
                                    <strong>
                                        {{ $note->created_at->format('d M Y') }}
                                    </strong>

                                    <small>
                                        {{ $note->created_at->format('h:i A') }}
                                    </small>
                                </div>

                            </div>

                            <x-ui.action-menu>

                                <button
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editNote{{ $note->id }}">

                                    <i class="fa-solid fa-pen"></i>
                                    Edit Note

                                </button>

                                <div class="dropdown-divider"></div>

                                <form
                                    method="POST"
                                    action="{{ route('companies.notes.destroy', [$company->uuid, $note->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this note? This action cannot be undone.">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-danger">

                                        <i class="fa-solid fa-trash"></i>
                                        Delete Note

                                    </button>

                                </form>

                            </x-ui.action-menu>

                        </div>

                        <div class="lp-note-content">
                            {!! nl2br(e(trim($note->note))) !!}
                        </div>

                        <div class="lp-note-footer">

                            <span>
                                <i class="fa-regular fa-clock"></i>
                                {{ $note->created_at->diffForHumans() }}
                            </span>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <x-ui.empty-state
                        icon="fa-regular fa-note-sticky"
                        title="No Notes Yet"
                        message="Capture meeting discussions, follow-ups, ideas and important client information in one place."
                        buttonText="Add First Note"
                        buttonTarget="#addNoteModal"
                    />

                </div>

            @endforelse

        </div>

    </div>

</div>


{{-- Edit Note Modals --}}
@foreach($notes as $note)

    <x-ui.modal
        id="editNote{{ $note->id }}"
        title="Edit Note">

        <form
            method="POST"
            action="{{ route('companies.notes.update', [$company->uuid, $note->uuid]) }}">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">
                    Note
                </label>

                <textarea
                    name="note"
                    rows="8"
                    class="form-control"
                    required>{{ old('note', trim($note->note)) }}</textarea>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="lp-btn lp-btn-primary">

                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Changes

                </button>

            </div>

        </form>

    </x-ui.modal>

@endforeach


{{-- Add Note Modal --}}
<x-ui.modal
    id="addNoteModal"
    title="Add Company Note">

    <form
        method="POST"
        action="{{ route('companies.notes.store', $company->uuid) }}">

        @csrf

        <div class="mb-3">

            <label class="form-label">
                Note
            </label>

            <textarea
                name="note"
                rows="8"
                class="form-control"
                placeholder="Write meeting notes, reminders, discussion summary..."
                required>{{ old('note') }}</textarea>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <button
                type="button"
                class="btn btn-light"
                data-bs-dismiss="modal">

                Cancel

            </button>

            <button
                type="submit"
                class="lp-btn lp-btn-primary">

                <i class="fa-solid fa-plus"></i>
                Add Note

            </button>

        </div>

    </form>

</x-ui.modal>