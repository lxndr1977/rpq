<?php

namespace App\Livewire\Pages;

use App\Models\Animal\Animal;
use Livewire\Component;

class AnimalsPage extends Component
{
    public function render()
    {
        $animals = Animal::all();
        return view('livewire.pages.animals-page', compact('animals'));
    }
}
