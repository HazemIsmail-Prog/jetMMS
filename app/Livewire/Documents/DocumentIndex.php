<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('documentsUpdated')]
    #[On('attachmentsUpdated')]
    public function documents()
    {
        return Document::query()
            ->withCount('attachments')
            ->with('documentType')
            ->with('receiver')
            ->paginate(100);
    }

    public function delete(Document $document)
    {
        $document->delete();
    }

    public function render()
    {
        return view('livewire.documents.document-index');
    }
}
