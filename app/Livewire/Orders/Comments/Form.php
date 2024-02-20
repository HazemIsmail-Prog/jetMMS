<?php

namespace App\Livewire\Orders\Comments;

use App\Models\Comment;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Form extends Component
{
    public Order $order;

    #[Rule('required')]
    public $comment;

    #[Computed()]
    public function comments()
    {
        return Comment::query()
            ->where('order_id', $this->order->id)
            ->with('user')
            ->get();
    }

    public function mount()
    {
        $this->scrollToBottom();
    }

    public function save()
    {
        $this->validate();
        Comment::create([
            'user_id' => auth()->id(),
            'order_id' => $this->order->id,
            'comment' => $this->comment,
        ]);
        $this->reset('comment');
        $this->scrollToBottom();
        $this->dispatch('commentsUpdated');
    }

    public function scrollToBottom() {
        $this->js("
        setTimeout(function() { 
            const el = document.getElementById('messages');
            el.scrollTop = el.scrollHeight - el.clientHeight;
         }, 100);

        ");
    }

    public function render()
    {
        return view('livewire.orders.comments.form');
    }
}
