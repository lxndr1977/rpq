<?php

namespace App\Http\Controllers\Animal;

use App\Enums\Animal\ActionEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Animal\Animal;

class AdoptionController extends Controller
{
    public function index()
    {
        return view('animals', [
            'route' => 'adoption.show', 
            'title' => 'Adote um animal', 
            'onlyAvailableForAdoption' => true,
        ]);        
    }

    public function show($slug)
    {
        $animal = Animal::where('slug', $slug)
            ->onlyActive()
            ->availableForAdoption()
            ->firstOrFail();
        
        return view('animal.adoption', compact('animal'));
    }
}
