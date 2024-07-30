<?php

namespace App\Livewire\PhoneDevices\Actions;

use App\Livewire\Forms\PhoneDeviceActionForm;
use App\Models\PhoneDevice;
use App\Models\PhoneDeviceAction;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ActionForm extends Component
{
    public $showModal = false;
    public PhoneDeviceAction $phoneDeviceAction;
    public PhoneDeviceActionForm $form;

    #[On('showActionFormModal')]
    public function show(PhoneDeviceAction $phoneDeviceAction, PhoneDevice $phoneDevice)
    {
        $this->resetErrorBag();
        $this->form->reset();
        $this->phoneDeviceAction = $phoneDeviceAction;
        $this->form->fill($this->phoneDeviceAction);
        $this->form->phone_device_id = $phoneDevice->id;

        // set from_id to latest to_id only on create
        if(!$phoneDeviceAction->id && $phoneDevice->latest_device_action?->to_id){
            $this->form->from_id = $phoneDevice->latest_device_action->to_id;
        }

        $this->showModal = true;
    }

    #[Computed()]
    public function users()
    {
        return User::query()
            ->select('id','name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    public function updated($key,$val) {
        if($key == 'form.from_id'){
            $this->form->to_id = null;
        }
        if($key == 'form.to_id'){
            $this->form->from_id = null;
        }
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->showModal = false;
        $this->dispatch('phoneDeviceActionsUpdated');
    }

    public function render()
    {
        return view('livewire.phone-devices.actions.action-form');
    }
}
