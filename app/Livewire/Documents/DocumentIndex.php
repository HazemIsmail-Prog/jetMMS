<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentIndex extends Component
{
    use WithPagination;

    public $listeners = [];
    public $filters = [
        'search' => '',
        'document_type_ids' => [],
        'receiver_ids' => [],
    ];

    #[Computed()]
    public function documentTypes()
    {
        return DocumentType::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->whereHas('documents')
            ->get();
    }
    #[Computed()]
    public function users()
    {
        return User::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->whereHas('documents')
            ->get();
    }

    #[Computed()]
    #[On('documentsUpdated')]
    #[On('attachmentsUpdated')]
    public function documents()
    {
        return Document::query()
            ->withCount('attachments')
            ->with('documentType')
            ->with('receiver')
            ->when($this->filters['search'], function (Builder $q) {
                $q->where('document_number', $this->filters['search']);
                $q->orWhere('document_serial_from', $this->filters['search']);
                $q->orWhere('document_serial_to', $this->filters['search']);
                $q->orWhere('document_pages', $this->filters['search']);
                $q->orWhere('notes', 'like', '%' . $this->filters['search'] . '%');
            })
            ->when($this->filters['document_type_ids'], function (Builder $q) {
                $q->whereIn('document_type_id', $this->filters['document_type_ids']);
            })
            ->when($this->filters['receiver_ids'], function (Builder $q) {
                $q->whereIn('receiver_id', $this->filters['receiver_ids']);
            })
            ->orderBy('document_type_id')
            ->orderBy('document_number', 'desc')
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
