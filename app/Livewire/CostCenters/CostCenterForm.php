<?php

namespace App\Livewire\CostCenters;

use App\Livewire\Forms\CostCenterForm as FormsCostCenterForm;
use App\Models\CostCenter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class CostCenterForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public CostCenter $cost_center;

    public FormsCostCenterForm $form;

    #[On('showCostCenterFormModal')]
    public function show(CostCenter $cost_center)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->cost_center = $cost_center;
        $this->modalTitle = $this->cost_center->id ? __('messages.edit_cost_center') . ' ' . $this->cost_center->name : __('messages.add_cost_center');
        $this->form->fill($this->cost_center);
    }

    public function save()
    {
        // dd($this->form);
        $validated = $this->form->validate();
        CostCenter::updateOrCreate(['id' => $validated['id']], $validated);
        $this->dispatch('costCentersUpdated');
        $this->showModal = false;
    }


    public function render()
    {
        return view('livewire.cost-centers.cost-center-form');
    }
}
