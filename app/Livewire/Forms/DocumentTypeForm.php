<?php

namespace App\Livewire\Forms;

use App\Models\DocumentType;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DocumentTypeForm extends Form
{
    public $id;
    public $name_ar;
    public $name_en;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name_ar' => 'required',
            'name_en' => 'required',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        DocumentType::updateOrCreate(['id' => $this->id], $this->all());
        $this->reset();
    }
}
