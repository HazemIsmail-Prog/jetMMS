<?php

namespace App\Livewire\Companies;

use App\Livewire\Forms\CompanyForm as FormsCompanyForm;
use App\Models\Company;
use Livewire\Attributes\On;
use Livewire\Component;

class CompanyForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Company $company;

    public FormsCompanyForm $form;

    #[On('showCompanyFormModal')]
    public function show(Company $company)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->company = $company;
        $this->modalTitle = $this->company->id ? __('messages.edit_company') . ' ' . $this->company->name : __('messages.add_company');
        $this->form->fill($this->company);
    }

    public function save()
    {
        $validated = $this->form->validate();
        Company::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('companiesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.companies.company-form');
    }
}
