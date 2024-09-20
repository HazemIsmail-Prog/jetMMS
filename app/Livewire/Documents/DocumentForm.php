<?php

namespace App\Livewire\Documents;

use App\Livewire\Forms\DocumentForm as FormsDocumentForm;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class DocumentForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Document $document;

    public FormsDocumentForm $form;

    #[Computed()]
    public function documentTypes()
    {
        return DocumentType::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function receivers()
    {
        return User::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[On('showDocumentFormModal')]
    public function show(Document $document)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->document = $document;
        $this->modalTitle = $this->document->id ? __('messages.edit_document') . ' ' . $this->document->name : __('messages.add_document');
        $this->form->fill($this->document);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('documentsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.documents.document-form');
    }
}
