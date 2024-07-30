<?php

namespace App\Livewire\PhoneDevices;

use App\Models\PhoneDevice;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DeviceIndex extends Component
{
    use WithPagination;

    public $filters = ['serial_no' => ''];

    #[Computed()]
    #[On('phoneDevicesUpdated')]
    #[On('phoneDeviceActionsUpdated')]
    #[On('attachmentsUpdated')]
    public function phoneDevices()
    {
        return PhoneDevice::query()
            ->with('latest_device_action.to.department')
            ->withCount('attachments')
            ->withCount('device_actions')
            ->when($this->filters['serial_no'], function ($q) {
                $q->where('serial_no', $this->filters['serial_no']);
            })
            ->paginate(200);
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function delete(PhoneDevice $phoneDevice) {
        $phoneDevice->delete();
    }

    public function render()
    {
        return view('livewire.phone-devices.device-index');
    }
}
