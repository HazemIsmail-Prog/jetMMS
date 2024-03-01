<?php

namespace App\Livewire\Areas;

use App\Models\Area;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AreaIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('areasUpdated')]
    public function areas()
    {
        return Area::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.areas.area-index');
    }
}
