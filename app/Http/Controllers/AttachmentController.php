<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use App\Http\Resources\AttachmentResource;
use App\Http\Requests\AttachmentRequest;
use App\Services\S3;
use Illuminate\Support\Facades\Storage;
class AttachmentController extends Controller
{
    public function index(Request $request)
    {
        $modelClass = "App\\Models\\" . $request->model_type;
        $model = $modelClass::findOrFail($request->model_id);
        $attachments = $model->attachments;
        return AttachmentResource::collection($attachments);
    }
    public function store(AttachmentRequest $request)
    {
        // create
        $modelClass = $request->attachable_type;
        $model = $modelClass::findOrFail($request->attachable_id);
        $attachment = Attachment::create($request->validated());
        $path = S3::saveToS3($request->file, $model);
        if ($path) {
            $attachment->file = $path;
            $attachment->save();
            return new AttachmentResource($attachment);
        }
    }

    public function update(AttachmentRequest $request, Attachment $attachment)
    {
        $attachment->update($request->except('file'));
        if ($request->file) {
            $path = S3::saveToS3($request->file, $attachment->attachable, $attachment->file);
            if ($path) {
                $attachment->file = $path;
                $attachment->save();
            }
        }
        return new AttachmentResource($attachment);
    }

    public function destroy(Attachment $attachment)
    {
        try {
            if (Storage::disk('s3')->exists($attachment->file)) {
                Storage::disk('s3')->delete($attachment->file);
            }
            $attachment->delete();
            return response()->json(['message' => 'Attachment deleted successfully']);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
