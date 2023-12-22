<?php

namespace App\Livewire\Statuses;

use App\Models\Status;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class StatusIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('statusesUpdated')]
    public function statuses()
    {
        return Status::query()
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.statuses.status-index');
    }
}
