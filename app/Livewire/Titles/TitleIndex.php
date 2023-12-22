<?php

namespace App\Livewire\Titles;

use App\Models\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TitleIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('titlesUpdated')]
    public function titles()
    {
        return Title::query()
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.titles.title-index');
    }
}
