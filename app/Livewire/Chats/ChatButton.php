<?php

namespace App\Livewire\Chats;

use Livewire\Attributes\Computed;
use Livewire\Component;

class ChatButton extends Component
{

    public function getListeners()
    {
        $authID = auth()->id();
        return [
            "echo:messages.{$authID},MessageSentEvent" => '$refresh',
            "markedAsRead" => '$refresh',
        ];
    }

    #[Computed()]
    public function total_unread_messages() {
        return auth()->user()->total_unread_messages;
    }

    public function render()
    {
        return view('livewire.chats.chat-button');
    }
}
