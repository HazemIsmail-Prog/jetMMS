<?php

namespace App\Http\Controllers;

use App\Http\Requests\LetterRequest;
use App\Http\Resources\LetterResource;
use App\Models\Letter;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $letters = Letter::query()
                ->withCount('attachments')
                ->when($request->type, function ($query, $type) {
                    return $query->where('type', $type);
                })
                ->orderBy('id', 'desc')
                ->paginate(30);
            return LetterResource::collection($letters);
        }
        return view('livewire.letters.index');
    }

    public function store(LetterRequest $request)
    {
        $letter = Letter::create($request->validated());
        return new LetterResource($letter);
    }

    public function update(LetterRequest $request, Letter $letter)
    {
        $letter->update($request->validated());
        return new LetterResource($letter);
    }

    public function destroy(Letter $letter)
    {
        // check if letter has attachments
        if ($letter->attachments->count() > 0) {
            return response()->json(['message' => 'Letter has attachments'], 400);
        }
        $letter->delete();
        return response()->json(['message' => 'Letter deleted successfully']);
    }

    public function show(Letter $letter)
    {
        return new LetterResource($letter->loadCount('attachments'));
    }
}
