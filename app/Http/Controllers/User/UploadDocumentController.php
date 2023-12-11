<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\UploadDocument;
use App\Services\DocumentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Support\Facades\Storage;

class UploadDocumentController extends Controller
{
    protected $document;
    public function __construct(DocumentService $document)
    {
        $this->document = $document;
    }

    public function uploadDocument(UploadDocument $uploadDocument)
    {
        $documents = $uploadDocument::with(['user'])
            ->when(
                !auth()->user()->isAdmin(),
                fn($q) => $q->where('user_id', auth()->user()->id)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('User/UploadDocument/uploaddocument', [
            'documents' => $documents,
        ]);
    }

    public function store(StoreFileRequest $storeFileRequest)
    {
        $this->document->storedocument($storeFileRequest);
    }

    public function verify($id, Request $request)
    {
        $this->authorize('admin.verify');

        $status = $request->get('status', false) == 'verified' ? true : false;

        $this->document->verifyDocument($id, $status);
    }

    public function destroy($id)
    {
        $this->document->deleteDocument($id);
    }

}
