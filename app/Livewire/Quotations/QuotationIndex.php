<?php

namespace App\Livewire\Quotations;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class QuotationIndex extends Component
{
    use WithPagination;

    #[Url()]
    public $filters =
    [
        'customer_name' => '',
        'quotation_number' => '',
        'creators' => [],
    ];

    #[Computed()]
    public function creators()
    {
        return User::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->whereHas('quotations')
            ->get();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getData()
    {
        return Quotation::query()
            ->with('user:id,name_ar,name_en')
            ->withCount('attachments')
            ->orderBy('quotation_number', 'desc')

            ->when($this->filters['customer_name'], function (Builder $q) {
                $q->where('customer_name', 'like', '%' . $this->filters['customer_name'] . '%');
            })
            ->when($this->filters['quotation_number'], function ($q) {
                $q->where('quotation_number', $this->filters['quotation_number']);
            })
            ->when($this->filters['creators'], function (Builder $q) {
                $q->whereIn('user_id', $this->filters['creators']);
            });
    }

    #[Computed]
    #[On('quotationsUpdated')]
    #[On('attachmentsUpdated')]
    public function quotations()
    {
        return $this->getData()
            ->paginate(10);
    }

    public function delete(Quotation $quotation) {
        $quotation->delete();
    }

    public function render()
    {
        return view('livewire.quotations.quotation-index');
    }
}
