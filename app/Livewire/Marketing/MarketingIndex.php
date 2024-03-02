<?php

namespace App\Livewire\Marketing;

use App\Models\Marketing;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MarketingIndex extends Component
{
    use WithPagination;

    public $listeners = [];
    #[Url()]
    public $filters;

    #[Computed()]
    public function creators()
    {
        return User::query()
            ->whereHas('marketings')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    public function mount()
    {
        $this->filters =
            [
                'name' => '',
                'phone' => '',
                'address' => '',
                'type' => '',
                'creators' => '',
                'start_created_at' => '',
                'end_created_at' => '',
            ];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed()]
    #[On('marketingsUpdated')]
    public function marketings()
    {
        return Marketing::query()
            ->with('user')
            ->orderBy('id', 'desc')

            ->when($this->filters['name'], function (Builder $q) {
                $q->where('name', 'like', '%' . $this->filters['name'] . '%');
            })
            ->when($this->filters['phone'], function (Builder $q) {
                $q->where('phone', 'like', $this->filters['phone'] . '%');
            })
            ->when($this->filters['address'], function (Builder $q) {
                $q->where('address', 'like', $this->filters['address'] . '%');
            })
            ->when($this->filters['creators'], function (Builder $q) {
                $q->where('user_id', $this->filters['creators']);
            })
            ->when($this->filters['type'], function (Builder $q) {
                $q->where('type', $this->filters['type']);
            })
            ->when($this->filters['start_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '>=', $this->filters['start_created_at']);
            })
            ->when($this->filters['end_created_at'], function (Builder $q) {
                $q->whereDate('created_at', '<=', $this->filters['end_created_at']);
            })

            ->paginate(15);
    }
    public function render()
    {
        return view('livewire.marketing.marketing-index');
    }
}
