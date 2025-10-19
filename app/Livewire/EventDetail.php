<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EventDetail extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }
    public function render()
    {
        return view('livewire.event-detail');
    }
}
