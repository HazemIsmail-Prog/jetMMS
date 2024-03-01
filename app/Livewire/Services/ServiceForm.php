<?php

namespace App\Livewire\Services;

use App\Livewire\Forms\ServiceForm as FormsServiceForm;
use App\Models\Department;
use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ServiceForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Service $service;

    public FormsServiceForm $form;

    #[On('showServiceFormModal')]
    public function show(Service $service)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->service = $service;
        $this->modalTitle = $this->service->id ? __('messages.edit_service') . ' ' . $this->service->name : __('messages.add_service');
        $this->form->fill($this->service);
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->select('id', 'name_ar', 'name_en','name_'.app()->getLocale().' as name')
            ->orderBy('name')
            ->where('is_service', true)
            ->get();
    }

    public function save()
    {
        $validated = $this->form->validate();
        Service::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('servicesUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.services.service-form');
    }
}
