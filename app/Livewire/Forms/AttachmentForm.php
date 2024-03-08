<?php

namespace App\Livewire\Forms;

use App\Models\Attachment;
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
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        $this->currentRecord = $this->attachable_type::find($this->attachable_id);
        if ($this->id) {
            // edit
            $currentAttachment = Attachment::find($this->id);
            if ($this->file !== $currentAttachment->file) {
                //remove old file from s3 and save the new one
                Storage::disk('s3')->delete($currentAttachment->file);
                $this->file = $this->saveToS3($this->file);
            }
            $currentAttachment->update($this->except('currentRecord'));
        } else {

            // create
            $this->file = $this->saveToS3($this->file);
            $this->currentRecord->attachments()->create($this->except('currentRecord'));
        }
        $this->reset();
    }

    public function saveToS3($file)
    {
        $storeFolder = 'Attachments/' . class_basename($this->currentRecord) . '/' . $this->currentRecord->id;
        $path = $file->storePublicly($storeFolder, 's3');
        return $path;
    }
}
