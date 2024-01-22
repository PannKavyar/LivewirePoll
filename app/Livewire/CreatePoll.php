<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Poll;

class CreatePoll extends Component
{
    public $title;
    public $options = ['First'];

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'options' => 'required|array|min:1|max:10', //for option array 
        'options.*' => 'required|min:1|max:255' //for each option element in this option array
    ];

    protected $messages = [
        'options.*' => 'The option can\'t be empty.'
    ];

    public function render()
    {
        return view('livewire.create-poll');
    }

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options); //array_values returns array that starting from index 0
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function createPoll()
    {
        $this->validate();

        Poll::create([
            'title' => $this->title
        ])->options()->createMany(
                collect($this->options)
                    ->map(fn($option) => ['name' => $option])
                    ->all()
            );
        $this->reset(['title', 'options']);
        $this->dispatch('pollCreated');
        //emit(dispatch) method is a Livewire function that 
        //sends a browser event to the Livewire JavaScript library on the front end.
    }
}