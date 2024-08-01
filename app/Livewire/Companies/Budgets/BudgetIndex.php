<?php

namespace App\Livewire\Companies\Budgets;

use App\Models\Company;
use App\Models\CompanyBudget;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BudgetIndex extends Component
{
    use WithPagination;

    public $filters;
    public $perPage = 10;

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }


    public function mount()
    {
        $this->filters =
            [
                'year' => '',
                'company_id' => [],
            ];
    }

    #[Computed()]
    #[On('companiesBudgetsUpdated')]
    #[On('attachmentsUpdated')]
    public function companies()
    {
        return Company::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get()
            ->sortBy('name');

    }

    #[Computed()]
    public function budgets()
    {
        return CompanyBudget::query()
            ->with('company')
            ->withCount('attachments')

            ->when($this->filters['company_id'], function (Builder $q) {
                $q->whereIn('company_id', $this->filters['company_id']);
            })

            ->when($this->filters['year'], function (Builder $q) {
                $q->where('year', 'like', '%'. $this->filters['year'] . '%');
            })

            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function delete(CompanyBudget $companyBudget) {
        $companyBudget->delete();
    }
    
    public function render()
    {
        return view('livewire.companies.budgets.budget-index');
    }
}
