<?php

namespace App\Livewire\Attachments;

use App\Livewire\Forms\AttachmentForm as FormsAttachmentForm;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AttachmentForm extends Component
{

    use WithFileUploads;
    public $showModal = false;
    public $modalTitle = '';
    public Attachment $attachment;
    public FormsAttachmentForm $form;

    #[On('showAttachmentFormModal')]
    public function show(Attachment $attachment, $model = null, $id = null)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->attachment = $attachment;
        $this->modalTitle = $this->attachment->id ? __('messages.edit_attachment') : __('messages.add_attachment');
        $this->form->fill($this->attachment);
        if (!$this->attachment->id) {
            // Create Mode
            $modelClass = 'App\\Models\\' . $model;
            $this->form->attachable_id = $id;
            $this->form->attachable_type = $modelClass;
        }
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('attachmentsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.attachments.attachment-form');
    }
}
