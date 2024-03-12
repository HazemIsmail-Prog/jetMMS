<?php

namespace App\Livewire\Shifts;

use App\Models\Shift;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ShiftIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('shiftsUpdated')]
    public function shifts()
    {
        return Shift::query()
            ->paginate(15);
    }

    public function delete(Shift $shift) {
        $shift->delete();
    }

    public function render()
    {
        return view('livewire.shifts.shift-index');
    }
}
