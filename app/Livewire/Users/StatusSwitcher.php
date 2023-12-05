<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class StatusSwitcher extends Component
{
    public User $user;
    public $status;
    
    public function mount()
    {
        $this->status = $this->user->active;
    }
    
    public function updatedStatus()
    {
        $this->user->active = !$this->user->active;
        $this->user->save();
        $this->dispatch('statusChanged');
    }

    public function render()
    {
        return view('livewire.users.status-switcher');
    }
}
