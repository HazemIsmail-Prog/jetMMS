<?php

namespace App\Livewire\Ratings;

use App\Models\Department;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class RatingIndex extends Component
{
    use WithPagination;

    public $filters =
    [
        'customer_name' => '',
        'customer_phone' => '',
        'order_number' => '',
        'rating' => '',
        'technicians' => [],
        'departments' => [],
        'start_completed_at' => '',
        'end_completed_at' => '',
    ];

    #[Computed()]
    public function creators()
    {
        return User::query()
            ->whereHas('orders_creator')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->whereHas('orders_technician')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->whereHas('orders')
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed]
    #[On('ratingsUpdated')]
    public function orders()
    {
        return Order::query()
            ->whereHas('rating')
            ->where('status_id', Status::COMPLETED)
            ->with('rating')
            ->with('department')
            ->with('technician')
            ->with('customer')
            ->with('phone')
            ->orderBy('id', 'desc')

            ->when($this->filters['customer_name'], function (Builder $q) {
                $q->whereRelation('customer', 'name', 'like', '%' . $this->filters['customer_name'] . '%');
            })
            ->when($this->filters['customer_phone'], function (Builder $q) {
                $q->whereRelation('phone', 'number', 'like', $this->filters['customer_phone'] . '%');
            })
            ->when($this->filters['order_number'], function ($q) {
                $q->where('id', $this->filters['order_number']);
            })
            ->when($this->filters['rating'], function ($q) {
                $q->whereHas('rating', function($q){
                    $q->where('rating',$this->filters['rating']);
                });
            })
            ->when($this->filters['technicians'], function (Builder $q) {
                $q->whereIn('technician_id', $this->filters['technicians']);
            })
            ->when($this->filters['departments'], function (Builder $q) {
                $q->whereIn('department_id', $this->filters['departments']);
            })
            ->when($this->filters['start_completed_at'], function (Builder $q) {
                $q->whereDate('completed_at', '>=', $this->filters['start_completed_at']);
            })
            ->when($this->filters['end_completed_at'], function (Builder $q) {
                $q->whereDate('completed_at', '<=', $this->filters['end_completed_at']);
            })

            ->paginate(100);
    }

    public function render()
    {
        return view('livewire.ratings.rating-index')->title(__('messages.ratings'));
    }
}
