<?php

namespace App\Livewire\Chats;

use App\Events\MessageReadEvent;
use App\Events\MessageSentEvent;
use App\Models\Message;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ChatModal extends Component
{
    public $showModal = false;
    public $search = '';
    public $modalTitle = '';
    #[Rule('required')]
    public $message = '';
    #[Rule('required')]
    public User $selectedUser;


    #[On('showChatModal')]
    public function show()
    {
        $this->selectedUser = null;
        $this->modalTitle = __('messages.invoices_for_order_number');
        $this->showModal = true;
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->where('id', '!=', auth()->id())
            ->where('active', true)
            ->orderBy('name_' . config('app.locale'))
            ->whereNotIn('title_id', [10, 11])
            ->when($this->search, function ($q) {
                $q->where('name_en', 'like', "%{$this->search}%");
                $q->orWhere('name_ar', 'like', "%{$this->search}%");
            })
            ->get();
    }

    public function selectUser(User $user)
    {
        $this->selectedUser = $user;
        $this->markAsRead();
        $this->dispatch('userSelected');
        $this->scrollToBottom();

        $this->js("
        setTimeout(function() { 
            document.getElementById('message').focus();
         }, 100);
        ");
    }

    public function markAsRead()
    {
        $unreadMessages =
            Message::where('read', false)
            ->where('sender_user_id', $this->selectedUser->id)
            ->where('receiver_user_id', auth()->id());

        if ($unreadMessages->count() > 0) {
            $unreadMessages
                ->update(['read' => true]);
            MessageReadEvent::dispatch($this->selectedUser->id);
            $this->dispatch('markedAsRead');
        }
    }

    public function getListeners()
    {
        $authID = auth()->id();
        return [
            "echo:messages.{$authID},MessageSentEvent" => 'broadcastedNotifications',
            "echo:messages.{$authID},MessageReadEvent" => '$refresh',
        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($this->showModal) {
            if ($event['sender_id'] == $this->selectedUser->id) {
                $this->selectUser($this->selectedUser);
            }
        }
    }

    #[Computed]
    #[On('userSelected')]
    public function selectedMessages()
    {
        return $this->selectedUser->messages()
            ->where(function ($q) {
                $q->where('receiver_user_id', auth()->id());
                $q->orWhere('sender_user_id', auth()->id());
            })
            ->get();
    }

    public function send()
    {
        $this->validate();
        Message::create([
            'sender_user_id' => auth()->id(),
            'receiver_user_id' => $this->selectedUser->id,
            'message' => $this->message,
        ]);
        $this->reset('message');
        $this->scrollToBottom();
        MessageSentEvent::dispatch($this->selectedUser->id, auth()->id());
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
        return view('livewire.chats.chat-modal');
    }
}
