<?php

namespace App\Livewire\Orders\Comments;

use App\Events\RefreshDepartmentScreenEvent;
use App\Events\RefreshOrderCommentsScreenEvent;
use App\Events\RefreshTechnicianScreenEvent;
use App\Models\Comment;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CommentForm extends Component
{
    public Order $order;

    public function getListeners()
    {
        return [
            "echo:comments.{$this->order->id},RefreshOrderCommentsScreenEvent" => 'listenToDispatch',
        ];
    }

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

    // #[On('echo:comments.{order.id},RefreshOrderCommentsScreenEvent')] // Alternative working way
    public function listenToDispatch() {
        $this->comments();
        $this->scrollToBottom();
    }

    public function mount()
    {
        $this->reset('comment');
        $this->scrollToBottom();
    }

    public function save()
    {
        $this->validate();
        Comment::create([
            'user_id' => auth()->id(),
            'order_id' => $this->order->id,
            'comment' => $this->comment,
        ]); // Observer Applied
        $this->reset('comment');
        $this->scrollToBottom();
        $this->dispatch('commentsUpdated');
    }

    public function scrollToBottom()
    {
        $this->js("
        setTimeout(function() { 
            const el = document.getElementById('messages');
            el.scrollTop = el.scrollHeight - el.clientHeight;
         }, 100);

        ");
    }

    public function render()
    {
        return view('livewire.orders.comments.comment-form');
    }
}
