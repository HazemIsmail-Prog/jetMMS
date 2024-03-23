<?php

namespace App\Livewire\Ratings;

use App\Models\Order;
use App\Models\Rating;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RatingForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public $notes = null;
    #[Rule('required')]
    public $rating = null;
    public Order $order;

    #[On('showRatingFormModal')]
    public function show(Order $order)
    {
        $this->reset('order', 'rating', 'notes');
        $this->order = $order;
        $this->rating = $this->order->rating->rating ?? null;
        $this->notes = $this->order->rating->notes ?? null;
        $this->modalTitle = __('messages.rate_order_no') . ' ' . $this->order->formated_id;;
        $this->showModal = true;
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();
        Rating::updateOrCreate(['order_id' => $this->order->id], [
            'rating' => $this->rating,
            'notes' => $this->notes,
            'created_by' => auth()->id(),
        ]);
        $this->dispatch('ratingsUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.ratings.rating-form');
    }
}
