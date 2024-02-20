<?php

namespace App\Livewire\Orders;

use App\Models\Comment;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CommentModal extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Order $order;

    #[On('showCommentsModal')]
    public function show($order_id)
    {
        $this->reset();
        $this->order = Order::find($order_id);
        $this->modalTitle = __('messages.comments_for_order_number') . $order_id;
        $this->showModal = true;

        $this->setCurrentCommentAsRead($order_id);
    }

    public function setCurrentCommentAsRead($order_id)
    {
        Comment::query()
            ->where('order_id', $order_id)
            ->where('is_read', false)
            ->where('user_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        $this->dispatch('commentsUpdated');
    }

    public function render()
    {
        return view('livewire.orders.comment-modal');
    }
}
