<div class="lp-module-card">

    <div class="lp-module-header">

        <div>
            <span class="lp-module-eyebrow">Files</span>

            <h4>Company Documents</h4>

            <p>Store proposals, quotations, invoices, agreements and other files.</p>
        </div>

        <button
            type="button"
            class="lp-btn lp-btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#uploadDocumentModal">

            <i class="fa-solid fa-cloud-arrow-up"></i>
            Upload Document
        </button>

    </div>

    <div class="lp-module-body">

        <div class="row g-4">

            @forelse($documents as $document)

                @php
                    $extension = strtolower(
                        pathinfo($document->original_filename, PATHINFO_EXTENSION)
                    );

                    $fileIcon = match($extension) {
                        'pdf' => 'fa-solid fa-file-pdf',
                        'doc', 'docx' => 'fa-solid fa-file-word',
                        'xls', 'xlsx', 'csv' => 'fa-solid fa-file-excel',
                        'ppt', 'pptx' => 'fa-solid fa-file-powerpoint',
                        'jpg', 'jpeg', 'png', 'gif', 'webp' => 'fa-solid fa-file-image',
                        'zip', 'rar', '7z' => 'fa-solid fa-file-zipper',
                        default => 'fa-solid fa-file-lines',
                    };

                    $fileClass = match($extension) {
                        'pdf' => 'lp-document-pdf',
                        'doc', 'docx' => 'lp-document-word',
                        'xls', 'xlsx', 'csv' => 'lp-document-excel',
                        'ppt', 'pptx' => 'lp-document-powerpoint',
                        'jpg', 'jpeg', 'png', 'gif', 'webp' => 'lp-document-image',
                        'zip', 'rar', '7z' => 'lp-document-archive',
                        default => 'lp-document-general',
                    };

                    $fileSize = $document->file_size >= 1048576
                        ? number_format($document->file_size / 1048576, 2) . ' MB'
                        : number_format($document->file_size / 1024, 1) . ' KB';

                    $fileUrl = asset('storage/' . $document->path);
                @endphp

                <div class="col-xl-4 col-md-6">

                    <div class="lp-premium-document-card">

                        <div class="lp-document-card-top">

                            <div class="lp-document-identity">

                                <div class="lp-document-icon {{ $fileClass }}">
                                    <i class="{{ $fileIcon }}"></i>
                                </div>

                                <div class="lp-document-title-wrap">

                                    <h5>{{ $document->title }}</h5>

                                    <div class="lp-document-badges">

                                        <span class="lp-document-category">
                                            {{ $document->category ?: 'General' }}
                                        </span>

                                        <span class="lp-document-extension">
                                            {{ strtoupper($extension ?: 'FILE') }}
                                        </span>

                                    </div>

                                </div>

                            </div>

                            <x-ui.action-menu>

                                <a
                                    href="{{ $fileUrl }}"
                                    target="_blank"
                                    rel="noopener noreferrer">

                                    <i class="fa-solid fa-eye"></i>
                                    View Document
                                </a>

                                <a href="{{ $fileUrl }}" download>
                                    <i class="fa-solid fa-download"></i>
                                    Download Document
                                </a>

                                <div class="dropdown-divider"></div>

                                <form
                                    method="POST"
                                    action="{{ route('companies.documents.destroy', [$company->uuid, $document->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this document? This action cannot be undone.">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-danger">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete Document
                                    </button>

                                </form>

                            </x-ui.action-menu>

                        </div>

                        <div class="lp-document-preview {{ $fileClass }}">

                            <i class="{{ $fileIcon }}"></i>

                            <span>{{ strtoupper($extension ?: 'FILE') }}</span>

                        </div>

                        <div class="lp-document-details">

                            <div class="lp-document-detail">

                                <span>
                                    <i class="fa-solid fa-file-signature"></i>
                                </span>

                                <div>
                                    <small>Original File</small>
                                    <strong title="{{ $document->original_filename }}">
                                        {{ $document->original_filename }}
                                    </strong>
                                </div>

                            </div>

                            <div class="lp-document-detail">

                                <span>
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>

                                <div>
                                    <small>Uploaded</small>
                                    <strong>{{ $document->created_at->format('d M Y') }}</strong>
                                </div>

                            </div>

                            <div class="lp-document-detail">

                                <span>
                                    <i class="fa-solid fa-hard-drive"></i>
                                </span>

                                <div>
                                    <small>File Size</small>
                                    <strong>{{ $fileSize }}</strong>
                                </div>

                            </div>

                        </div>

                        <div class="lp-document-card-footer">

                            <a
                                href="{{ $fileUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="lp-document-action-btn">

                                <i class="fa-solid fa-eye"></i>
                                View
                            </a>

                            <a
                                href="{{ $fileUrl }}"
                                download
                                class="lp-document-action-btn lp-document-download-btn">

                                <i class="fa-solid fa-download"></i>
                                Download
                            </a>

                            <form
                                method="POST"
                                action="{{ route('companies.documents.destroy', [$company->uuid, $document->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this document? This action cannot be undone.">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="lp-document-action-btn lp-document-delete-btn">

                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <x-ui.empty-state
                        icon="fa-solid fa-folder-open"
                        title="No Documents"
                        subtitle="Upload the first document for this company."
                    />

                </div>

            @endforelse

        </div>

    </div>

</div>

<x-ui.modal
    id="uploadDocumentModal"
    title="Upload Company Document">

    <form
        method="POST"
        enctype="multipart/form-data"
        action="{{ route('companies.documents.store', $company->uuid) }}">

        @csrf

        <div class="row g-3">

            <div class="col-md-7">

                <label class="form-label">
                    Document Title <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    required>

            </div>

            <div class="col-md-5">

                <label class="form-label">Category</label>

                <select name="category" class="form-select">

                    @foreach([
                        'General',
                        'Proposal',
                        'Quotation',
                        'Invoice',
                        'Agreement',
                        'Purchase Order',
                        'Presentation'
                    ] as $category)

                        <option
                            value="{{ $category }}"
                            @selected(old('category') === $category)>

                            {{ $category }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-12">

                <label class="form-label">
                    Select File <span class="text-danger">*</span>
                </label>

                <div class="lp-document-upload-box">

                    <i class="fa-solid fa-cloud-arrow-up"></i>

                    <strong>Select a document to upload</strong>

                    <span>PDF, Word, Excel, PowerPoint, image or archive</span>

                    <input
                        type="file"
                        name="file"
                        class="form-control"
                        required>

                </div>

            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <button
                type="button"
                class="btn btn-light"
                data-bs-dismiss="modal">

                Cancel
            </button>

            <button type="submit" class="lp-btn lp-btn-primary">

                <i class="fa-solid fa-upload"></i>
                Upload Document
            </button>

        </div>

    </form>

</x-ui.modal>