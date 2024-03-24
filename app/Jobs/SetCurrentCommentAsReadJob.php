<?php

namespace App\Jobs;

use App\Models\Comment;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Livewire\Livewire;

class SetCurrentCommentAsReadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        Comment::query()
            ->where('order_id', $this->order->id)
            ->where('is_read', false)
            ->where('user_id', '!=', auth()->id())
            ->update(['is_read' => true]);



    }
}
