<?php

namespace App\Livewire\Chats;

use Livewire\Component;

class ChatButton extends Component
{
    protected $listeners = [
        'userSelected' =>'$refresh'
    ];
    public function render()
    {
        return view('livewire.chats.chat-button');
    }
}
