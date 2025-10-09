<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\Attributes\Layout;

class EventList extends Component
{
    #[Layout('layouts.app')]

    public function render()
    {
        // $events = Event::where('status', 'published')->latest()->get();
        $events = Event::latest()->get();
        return view('livewire.event-list', compact('events'));
    }
}
