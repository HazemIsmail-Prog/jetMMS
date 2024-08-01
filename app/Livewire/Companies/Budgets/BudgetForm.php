<?php

namespace App\Livewire\Companies\Budgets;

use App\Livewire\Forms\CompanyBudgetForm;
use App\Models\Company;
use App\Models\CompanyBudget;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class BudgetForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public CompanyBudget $companyBudget;

    public CompanyBudgetForm $form;

    #[On('showCompanyBudgetFormModal')]
    public function show(CompanyBudget $companyBudget)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->companyBudget = $companyBudget;
        $this->modalTitle = $this->companyBudget->id ? __('messages.edit_company_budget') . ' ' . $this->companyBudget->name : __('messages.add_company_budget');
        $this->form->fill($this->companyBudget);
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
        $this->dispatch('companiesBudgetsUpdated');
        $this->showModal = false;
    }

    
    public function render()
    {
        return view('livewire.companies.budgets.budget-form');
    }
}
