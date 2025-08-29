<?php

namespace App\Livewire\Cars;

use App\Models\Car;
use App\Models\Company;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CarIndex extends Component
{
    use WithPagination;

    public $filters = [
        'car_code' => '',
        'year' => '',
        'company_id' => [],
    ];

    #[Computed()]
    public function companies()
    {
        return Company::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    #[On('carsUpdated')]
    #[On('carActionsUpdated')]
    #[On('carServicesUpdated')]
    #[On('attachmentsUpdated')]
    public function cars()
    {
        return Car::query()
            ->with('brand')
            ->with('company')
            ->with('type')
            ->with('latest_car_action.to.department')
            ->withCount('attachments')
            ->withCount('car_actions')
            ->withCount('car_services')
            ->withSum('car_services','cost')
            ->when($this->filters['car_code'], function ($q) {
                $q->where('code', $this->filters['car_code']);
            })
            ->when($this->filters['year'], function ($q) {
                $q->where('year', $this->filters['year']);
            })
            ->when($this->filters['company_id'], function ($q) {
                $q->whereIn('company_id', $this->filters['company_id']);
            })
            ->orderBy('notes')
            ->paginate(500);
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function delete(Car $car) {
        $car->delete();
    }

    public function render()
    {
        return view('livewire.cars.car-index')->title(__('messages.cars'));
    }
}
