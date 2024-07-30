<?php

namespace App\Livewire\PhoneDevices\Actions;

use App\Models\Car;
use App\Models\CarAction;
use App\Models\PhoneDevice;
use App\Models\PhoneDeviceAction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ActionIndex extends Component
{
    use WithPagination, WithoutUrlPagination;

    public PhoneDevice $phoneDevice;
    public $showModal = false;

    #[On('showPhoneDeviceActionsModal')]
    public function show(PhoneDevice $phoneDevice) {
        $this->phoneDevice = $phoneDevice;
        $this->resetPage();
        $this->showModal = true;
        
    }

    #[Computed]
    #[On('phoneDeviceActionsUpdated')]
    public function actions() {
        return PhoneDeviceAction::query()
        ->where('phone_device_id',$this->phoneDevice->id)
        ->with('from')
        ->with('to')
        ->orderBy('date','desc')
        ->orderBy('time','desc')
        ->paginate(10)
        ;
    }

    public function delete(PhoneDeviceAction $phoneDeviceAction) {
        $phoneDeviceAction->delete();
        $this->dispatch('phoneDeviceActionsUpdated');
    }

    public function render()
    {
        return view('livewire.phone-devices.actions.action-index');
    }
}
