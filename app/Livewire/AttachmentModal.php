<?php

namespace App\Livewire;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class AttachmentModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $showForm = false;
    public $modalTitle = '';
    public $currentRecord;
    public $attachable_id;
    public $attachment = [];

    public function rules()
    {
        return [
            'attachment.description_ar' => 'required',
            'attachment.description_en' => 'required',
            'attachment.file' => 'required',
            'attachment.expirationDate' => 'required_if:attachment.alertable,=,"true"',
            'attachment.alertable' => 'nullable',
            'attachment.alertBefore' => 'required_if:attachment.alertable,=,"true"',
        ];
    }

    #[On('showAttachmentModal')]
    public function show($model, $id)
    {
        $this->reset();
        $modelClass = 'App\\Models\\' . $model;
        $this->currentRecord = $modelClass::find($id);
        $this->modalTitle = __('messages.attachments');
        $this->showModal = true;
    }
    
    public function show_form() {
        $this->reset('attachment');
        $this->resetErrorBag();
        $this->attachment['alertable'] = false;
        $this->attachment['file'] = null;
        $this->showForm = true;
        $this->js("
        setTimeout(function() { 
            document.getElementById('description_ar').focus();
         }, 100);
        ");
    }

    #[Computed()]
    public function attachments()
    {
        return $this->currentRecord->attachments;
    }

    public function save()
    {
        $validated = $this->validate();
        if (isset($this->attachment['id'])) {

            // edit
            $currentAttachment = Attachment::find($this->attachment['id']);
            if ($this->attachment['file'] !== $currentAttachment->file) {
                //remove old file from s3 and save the new one
                Storage::disk('s3')->delete($currentAttachment->file);
                $validated['attachment']['file'] = $this->saveToS3($validated['attachment']['file']);
            }
            $currentAttachment->update($validated['attachment']);

        } else {

            // create
            $validated['attachment']['file'] = $this->saveToS3($validated['attachment']['file']);
            $this->currentRecord->attachments()->create($validated['attachment']);
            
        }
        $this->reset(['showForm', 'attachment']);
        $this->dispatch('attachmentsUpdated');
    }

    public function saveToS3($file)
    {
        $storeFolder = 'Attachments/' . class_basename($this->currentRecord) . '/' . $this->currentRecord->id;
        $path = $file->storePublicly($storeFolder, 's3');
        return $path;
    }

    public function edit(Attachment $attachment)
    {
        $this->reset('attachment');
        $this->resetErrorBag();
        $this->showForm = true;
        $this->attachment = $attachment->toArray();
    }

    public function delete(Attachment $attachment)
    {
        Storage::disk('s3')->delete($attachment->file);
        $attachment->delete();
        $this->dispatch('attachmentsUpdated');

    }
    public function render()
    {
        return view('livewire.attachment-modal');
    }
}
