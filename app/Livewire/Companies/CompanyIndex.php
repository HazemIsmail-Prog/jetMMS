<?php

namespace App\Livewire\Companies;

use App\Models\Company;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('companiesUpdated')]
    public function companies()
    {
        return Company::query()
            ->paginate(15);
    }

    public function delete(Company $company) {
        $company->delete();
    }

    public function render()
    {
        return view('livewire.companies.company-index')->title(__('messages.companies'));
    }
}
