<?php

namespace App\Livewire\Forms;

use App\Models\Attachment;
use App\Services\S3;
use Illuminate\Support\Facades\Storage;
use Livewire\Form;

class AttachmentForm extends Form
{
    public $id;
    public $attachable_id;
    public $attachable_type;
    public $description_ar;
    public $description_en;
    public $file;
    public $expirationDate;
    public bool $alertable = false;
    public $alertBefore;
    public $currentRecord;
    public bool $successUpload = true;

    public function rules()
    {
        return [
            'attachable_id' => 'required',
            'attachable_type' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'file' => 'required',
            'expirationDate' => 'required_if:alertable,=,"true"',
            'alertable' => 'nullable',
            'alertBefore' => 'required_if:alertable,=,"true"',
            'successUpload' => ['required', 'in:1'], // Using the 'in' validation rule
        ];
    }

    public function messages() {
        return [
            'successUpload.in' => __('messages.something went wrong'),
        ];
    }

    public function updateOrCreate()
    {
        $this->successUpload = true;
        $this->validate();
        $this->currentRecord = $this->attachable_type::find($this->attachable_id);
        if ($this->id) {
            // edit
            $currentAttachment = Attachment::find($this->id);
            $path = S3::saveToS3($this->file, $this->currentRecord, $currentAttachment->file);
            if (!$path) {
                $this->successUpload = false;
                $this->validateOnly('successUpload');
            }
            $this->file = $path;
            $currentAttachment->update($this->except('currentRecord','successUpload'));
        } else {

            // create
            $path = S3::saveToS3($this->file, $this->currentRecord);
            if (!$path) {
                $this->successUpload = false;
                $this->validateOnly('successUpload');
            }
            $this->file = $path;
            $this->currentRecord->attachments()->create($this->except('currentRecord','successUpload'));
        }
        $this->reset();
    }
}
