<?php

namespace App\Livewire\Forms;

use App\Models\Document;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DocumentForm extends Form
{
    public $id;
    public $document_type_id;
    public $receiver_id;
    public $created_by;
    public $document_number;
    public $document_serial_from;
    public $document_serial_to;
    public $document_pages;
    public $status = 'active';
    public $receiving_date;
    public $back_date;
    public $notes;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'document_type_id' => 'required',
            'receiver_id' => 'required',
            'created_by' => 'nullable',
            'document_number' => 'required',
            'document_serial_from' => 'required',
            'document_serial_to' => 'required',
            'document_pages' => 'required',
            'status' => 'required',
            'receiving_date' => 'nullable',
            'back_date' => 'nullable',
            'notes' => 'nullable',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        if (!$this->id) {
            $this->created_by = auth()->id();
        }
        Document::updateOrCreate(['id' => $this->id], $this->all());
        $this->reset();
    }
}
