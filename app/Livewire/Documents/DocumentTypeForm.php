<?php

namespace App\Livewire\Documents;

use App\Livewire\Forms\DocumentTypeForm as FormsDocumentTypeForm;
use App\Models\DocumentType;
use Livewire\Attributes\On;
use Livewire\Component;

class DocumentTypeForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public DocumentType $documentType;

    public FormsDocumentTypeForm $form;

    #[On('showDocumentTypeFormModal')]
    public function show(DocumentType $documentType)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->documentType = $documentType;
        $this->modalTitle = $this->documentType->id ? __('messages.edit_document_type') . ' ' . $this->documentType->name : __('messages.add_document_type');
        $this->form->fill($this->documentType);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('documentTypesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.documents.document-type-form');
    }
}
