<?php

namespace App\Services;

use App\Models\UploadDocument;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function storedocument(StoreFileRequest $storeFileRequest)
    {
        if ($storeFileRequest->has('file')) {
            $file = $storeFileRequest->file('file');
            $path = $file->store('uploads', 'public');
            UploadDocument::create([
                'user_id' => auth()->user()->id,
                'filename' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]);
        }
    }

    public function verifyDocument($id, $status): void
    {
        UploadDocument::findOrFail($id)
            ->update([
                'status' => $status
            ]);
    }

    public function downloadDocument()
    {
        $user = auth()->user();
        if ($user->uploadedDocuments()->exist()) {
            return response()
                ->json(['error' => 'You can only upload one document'], 422);
        }
    }

    public function deleteDocument($id)
    {
        $file = UploadDocument::findOrFail($id);

        Storage::disk('public')->delete($file->file_path);

        $file->delete();
    }
}
