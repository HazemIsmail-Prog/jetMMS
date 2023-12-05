<?php

namespace App\Livewire\Chats;

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
        $this->modalTitle = __('messages.invoices_for_order_number');
        $this->showModal = true;
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->where('id', '!=', auth()->id())
            ->where('active',true)
            ->whereNotIn('title_id',[10,11])
            ->when($this->search,function($q){
                $q->where('name_en','like',"%{$this->search}%");
                $q->orWhere('name_ar','like',"%{$this->search}%");
            })
            ->get();
    }

    public function selectUser(User $user)
    {
        $this->selectedUser = $user;
        Message::where('read',false)
        ->where('sender_user_id',$this->selectedUser->id)
        ->where('receiver_user_id',auth()->id())
        ->update(['read'=>true])
        ;
        $this->dispatch('userSelected');
        $this->js("
        setTimeout(function() { 
            const el = document.getElementById('messages');
            el.scrollTop = el.scrollHeight - el.clientHeight;
         }, 100);
        ");
        $this->js("
        setTimeout(function() { 
            document.getElementById('message').focus();
         }, 100);
        ");
    }

    #[Computed]
    #[On('messageSent')]
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
        $this->dispatch('messageSent');
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
