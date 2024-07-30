<?php

namespace App\Livewire\PhoneDevices;

use App\Livewire\Forms\PhoneDeviceForm;
use App\Models\PhoneDevice;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class DeviceForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public PhoneDevice $phoneDevice;

    public PhoneDeviceForm $form;

    #[On('showPhoneDeviceFormModal')]
    public function show(PhoneDevice $phoneDevice)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->phoneDevice = $phoneDevice;
        $this->modalTitle = $this->phoneDevice->id ? __('messages.edit_phoneDevice') . ' ' . $this->phoneDevice->code : __('messages.add_phoneDevice');
        $this->form->fill($this->phoneDevice);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('phoneDevicesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.phone-devices.device-form');
    }
}
