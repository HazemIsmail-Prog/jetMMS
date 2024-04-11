<?php

namespace App\Livewire\Services;

use App\Models\Department;
use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceIndex extends Component
{
    use WithPagination;

    public $listeners = [];
    public $filters;

    public function mount()
    {
        $this->filters = [
            'name' => '',
            'department_id' => [],
            'type' => '',
        ];
    }

    #[Computed()]
    #[On('servicesUpdated')]
    public function services()
    {
        return Service::query()
            ->with('department')
            ->when($this->filters['name'], function ($q) {
                $q->where('name_ar', 'like', '%' . $this->filters['name'] . '%');
                $q->orWhere('name_en', 'like', '%' . $this->filters['name'] . '%');
            })
            ->when($this->filters['department_id'], function ($q) {
                $q->whereIn('department_id', $this->filters['department_id']);
            })
            ->when($this->filters['type'], function ($q) {
                $q->where('type', $this->filters['type']);
            })
            ->paginate(15);
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->where('is_service', true)
            ->orderBy('name')
            ->get();
    }

    public function delete(Service $service) {
        $service->delete();
    }

    public function render()
    {
        return view('livewire.services.service-index');
    }
}
