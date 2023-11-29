<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\UploadDocument;
use App\Services\DocumentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileRequest;
class UploadDocumentController extends Controller
{
    protected $document;
    public function __construct(DocumentService $document)
    {
        $this->document = $document;
    }

    public function upload(UploadDocument $uploadDocument)
    {
        $documents = $uploadDocument::with(['user'])
            ->when(
                !auth()->user()->isAdmin(),
                fn($q) => $q->where('user_id', auth()->user()->id)
            )
            ->get();

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

    public function view($filename)
    {
        $filePath = public_path('uploads/' . $filename);

        if (file_exists($filePath)) {
            return response()->file($filePath);
        } else {
            abort(404);
        }
    }

    public function destroy($id)
    {
        $this->document->deleteDocument($id);
    }

}
