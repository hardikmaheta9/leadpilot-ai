<div class="lp-documents-card">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h5 class="mb-1">Company Documents</h5>
            <small class="text-muted">
                Store proposals, agreements, quotations, invoices and other files.
            </small>
        </div>

    </div>

    <form method="POST"
          enctype="multipart/form-data"
          action="{{ route('companies.documents.store',$company->uuid) }}">

        @csrf

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">Document Title</label>
                <input
                    name="title"
                    class="form-control"
                    required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Category</label>

                <select
                    name="category"
                    class="form-select">

                    <option>General</option>
                    <option>Proposal</option>
                    <option>Quotation</option>
                    <option>Invoice</option>
                    <option>Agreement</option>
                    <option>Purchase Order</option>
                    <option>Presentation</option>

                </select>

            </div>

            <div class="col-md-5 mb-3">
                <label class="form-label">Select File</label>

                <input
                    type="file"
                    name="file"
                    class="form-control"
                    required>
            </div>

        </div>

        <button class="btn btn-primary">
            <i class="fa-solid fa-upload me-2"></i>
            Upload Document
        </button>

    </form>

    <hr class="my-4">

    <div class="row">

        @forelse($documents as $document)

            <div class="col-lg-6 mb-3">

                <div class="lp-document-card">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6 class="mb-1">

                                {{ $document->title }}

                            </h6>

                            <small class="text-muted">

                                {{ $document->category }}

                            </small>

                        </div>

                        <span class="badge bg-primary">

                            {{ strtoupper(pathinfo($document->original_filename, PATHINFO_EXTENSION)) }}

                        </span>

                    </div>

                    <hr>

                    <div class="small text-muted">

                        <div>

                            <i class="fa-solid fa-file me-2"></i>

                            {{ $document->original_filename }}

                        </div>

                        <div class="mt-2">

                            <i class="fa-solid fa-calendar me-2"></i>

                            {{ $document->created_at->format('d M Y') }}

                        </div>

                        <div class="mt-2">

                            <i class="fa-solid fa-hard-drive me-2"></i>

                            {{ number_format($document->file_size/1024,1) }} KB

                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">

                        <a
                            href="{{ asset('storage/'.$document->path) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">

                            View

                        </a>

                        <a
                            href="{{ asset('storage/'.$document->path) }}"
                            download
                            class="btn btn-sm btn-outline-success">

                            Download

                        </a>

                        <form
                            method="POST"
                            action="{{ route('companies.documents.destroy',[$company->uuid,$document->uuid]) }}"
                            onsubmit="return confirm('Delete document?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger">

                                Delete

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        @empty

            <div class="text-center py-5">

                <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>

                <h5>No Documents</h5>

                <p class="text-muted">
                    Upload your first company document.
                </p>

            </div>

        @endforelse

    </div>

</div>