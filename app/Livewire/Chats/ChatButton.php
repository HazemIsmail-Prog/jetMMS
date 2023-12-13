<?php

namespace App\Livewire\Chats;

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

    public function render()
    {
        return view('livewire.chats.chat-button');
    }
}
