<?php

namespace App\Livewire\Companies\Contracts;

use App\Models\Company;
use App\Models\CompanyContract;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ContractIndex extends Component
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
                'client_name' => '',
                'company_id' => [],
            ];
    }

    #[Computed()]
    #[On('companiesContractsUpdated')]
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
    public function contracts()
    {
        return CompanyContract::query()
            ->with('company')
            ->withCount('attachments')

            ->when($this->filters['company_id'], function (Builder $q) {
                $q->whereIn('company_id', $this->filters['company_id']);
            })

            ->when($this->filters['client_name'], function (Builder $q) {
                $q->where('client_name', 'like', '%'. $this->filters['client_name'] . '%');
            })

            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function delete(CompanyContract $companyContract) {
        $companyContract->delete();
    }


    public function render()
    {
        return view('livewire.companies.contracts.contract-index');
    }
}
