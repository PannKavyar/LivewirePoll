<?php

namespace App\Livewire;

use Livewire\Component;

class Polls extends Component
{
    protected $listeners = [
        'pollCreated' => 'render'
    ];

    public function render()
    {
        $polls = \App\Models\Poll::with('options.votes')
            ->latest()->get();

        return view('livewire.polls', ['polls' => $polls]);
    }

    public function vote($optionId)
    {
        $option = \App\Models\Option::findOrFail($optionId);
        $option->votes()->create();
    }
}