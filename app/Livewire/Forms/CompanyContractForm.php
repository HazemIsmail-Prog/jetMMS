<?php

namespace App\Livewire\Forms;

use App\Models\CompanyContract;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CompanyContractForm extends Form
{
    public $id;
    public $company_id;
    public $user_id;
    public $client_name;
    public $initiation_date;
    public $expiration_date;
    public $description;
    public $notes;
    public bool $active = true;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'company_id' => 'required',
            'user_id' => 'required',
            'client_name' => 'required',
            'initiation_date' => 'nullable',
            'expiration_date' => 'nullable',
            'description' => 'nullable',
            'notes' => 'nullable',
            'active' => 'nullable',
        ];
    }

    public function updateOrCreate() {
        if (!$this->id) {
            $this->user_id = auth()->id();
        }
        $this->validate();
        CompanyContract::updateOrCreate(['id' => $this->id], $this->all());
    }
}
