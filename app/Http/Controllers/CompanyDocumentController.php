<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyDocumentController extends Controller
{
    public function store(Request $request, string $companyUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');

        $storedFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('company-documents/' . $company->uuid, $storedFilename, 'public');

        Document::create([
            'company_uuid' => $company->uuid,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => $storedFilename,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'disk' => 'public',
            'path' => $path,
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'documents'])
            ->with('success', 'Document uploaded successfully.');
    }

    public function destroy(string $companyUuid, string $documentUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $document = Document::where('uuid', $documentUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        Storage::disk($document->disk)->delete($document->path);

        $document->delete();

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'documents'])
            ->with('success', 'Document deleted successfully.');
    }
}