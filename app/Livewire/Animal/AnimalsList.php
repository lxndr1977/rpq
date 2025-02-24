<?php

namespace App\Livewire\Animal;

use Livewire\Component;
use App\Models\Animal\Animal;
use App\Enums\Animal\ActionEnum;

class AnimalsList extends Component
{
    public $route = '';
    public $specie = '';
    public $size = '';
    public $gender = '';
    public $onlyAvailableForAdoption = false;

    public function mount($route, $onlyAvailableForAdoption)
    {
        $this->route = $route;
        $this->onlyAvailableForAdoption = $onlyAvailableForAdoption;
    }
    
    public function applyFilters()
    {
        $this->render();
    }

    public function clearFilters(): void
    {
        $this->specie = '';
        $this->size = '';
        $this->gender = '';
    }

    public function render()
    {
        $query = Animal::query()
            ->orderBy('name')
            ->select(['id', 'name', 'slug', 'specie', 'gender', 'size', 'status'])
            ->onlyActive();

        if ($this->onlyAvailableForAdoption) {
            $query->availableForAdoption();
        }

        if ($this->specie) {
            $query->where('specie', $this->specie);
        }

        if ($this->size) {
            $query->where('size', $this->size);
        }

        if ($this->gender) {
            $query->where('gender', $this->gender);
        }

        $animals = $query->get();

        return view('livewire.animal.animals-list', compact('animals'));
    }
}
