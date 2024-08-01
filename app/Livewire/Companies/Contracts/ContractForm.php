<?php

namespace App\Livewire\Companies\Contracts;

use App\Livewire\Forms\CompanyContractForm;
use App\Models\Company;
use App\Models\CompanyContract;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ContractForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public CompanyContract $companyContract;

    public CompanyContractForm $form;

    #[On('showCompanyContractFormModal')]
    public function show(CompanyContract $companyContract)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->companyContract = $companyContract;
        $this->modalTitle = $this->companyContract->id ? __('messages.edit_company_contract') . ' ' . $this->companyContract->name : __('messages.add_company_contract');
        $this->form->fill($this->companyContract);
    }

    #[Computed()]
    public function companies()
    {
        return Company::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get()
            ->sortBy('name');

    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('companiesContractsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.companies.contracts.contract-form');
    }
}
