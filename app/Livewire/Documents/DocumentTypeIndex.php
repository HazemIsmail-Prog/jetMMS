<?php

namespace App\Livewire\Documents;

use App\Models\DocumentType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentTypeIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('documentTypesUpdated')]
    public function documentTypes()
    {
        return DocumentType::query()
            ->paginate(1500);
    }

    public function delete(DocumentType $documentType)
    {
        $documentType->delete();
    }

    public function render()
    {
        return view('livewire.documents.document-type-index');
    }
}
