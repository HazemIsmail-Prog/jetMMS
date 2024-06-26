<?php

namespace App\Livewire;

use App\Models\Department;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Sidebar extends Component
{

    #[Computed(cache: true, key: 'sidebar-departments')]
    public function departments()
    {
        return Department::where('is_service', true)->get();
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}
