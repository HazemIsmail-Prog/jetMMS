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
    public $showS3Error = false;
    public $model;
    public $currentRecord;

    #[On('showAttachmentModal')]
    public function show($model, $id)
    {
        $this->reset('showS3Error');
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
        try {
            if (Storage::disk('s3')->exists($attachment->file)) {
                Storage::disk('s3')->delete($attachment->file);
            }
            $attachment->delete();
            $this->showS3Error = false;
            $this->dispatch('attachmentsUpdated');
        } catch (\Throwable $th) {
            $this->showS3Error = true;
        }
    }
    public function render()
    {
        return view('livewire.attachments.attachment-index');
    }
}
