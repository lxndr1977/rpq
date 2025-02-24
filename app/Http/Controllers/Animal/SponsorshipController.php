<?php

namespace App\Http\Controllers\Animal;

use Illuminate\Http\Request;
use App\Models\Animal\Animal;
use App\Http\Controllers\Controller;
use App\Enums\Animal\ActionEnum;

class SponsorshipController extends Controller
{
    public function index()
    {
        return view('animals', [
                'route' => 'sponsorship.show', 
                'title' => 'Apadrinhe um animal', 
                'onlyAvailableForAdoption' => false,
            ]);    
    }

    public function show($slug)
    {
        $animal = Animal::with('activeExpenses')
            ->where('slug', $slug)
            ->onlyActive()
            ->firstOrFail();
    
        return view('animal.sponsorship', compact('animal'));
    }
}
