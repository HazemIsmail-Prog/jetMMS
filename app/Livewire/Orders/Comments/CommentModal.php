<?php

namespace App\Livewire\Orders\Comments;

use App\Models\Comment;
use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class CommentModal extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Order $order;

    #[On('showCommentsModal')]
    public function show(Order $order)
    {
        $this->order = $order;
        $this->modalTitle = __('messages.comments_for_order_number') . str_pad($this->order->id, 8, '0', STR_PAD_LEFT);
        $this->showModal = true;

        $this->setCurrentCommentAsRead($this->order);
    }

    public function setCurrentCommentAsRead(Order $order)
    {
        Comment::query()
            ->where('order_id', $order->id)
            ->where('is_read', false)
            ->where('user_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        $this->dispatch('commentsUpdated');
    }

    public function render()
    {
        return view('livewire.orders.comments.comment-modal');
    }
}
