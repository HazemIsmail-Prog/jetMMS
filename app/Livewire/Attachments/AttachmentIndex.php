<?php

namespace App\Livewire\Attachments;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class AttachmentIndex extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public $model;
    public $currentRecord;

    #[On('showAttachmentModal')]
    public function show($model, $id)
    {
        $this->model = $model;
        $modelClass = 'App\\Models\\' . $this->model;
        $this->currentRecord = $modelClass::find($id);
        $this->modalTitle = __('messages.attachments');
        $this->showModal = true;
    }
    
    #[Computed()]
    #[On('attachmentsUpdated')]
    public function attachments()
    {
        return $this->currentRecord->attachments;
    }

    public function delete(Attachment $attachment)
    {
        Storage::disk('s3')->delete($attachment->file);
        $attachment->delete();
        $this->dispatch('attachmentsUpdated');

    }
    public function render()
    {
        return view('livewire.attachments.attachment-index');
    }
}
