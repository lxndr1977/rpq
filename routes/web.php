<?php

use App\Livewire\Pages\AnimalsPage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Animal\AdoptionController;
use App\Http\Controllers\Animal\SponsorshipController;

Route::view('/', 'home');
Route::view('/welcome', 'welcome');

Route::group(['prefix' => 'adote'], function () {
    Route::get('/', [AdoptionController::class, 'index'])->name('adoption.index');
    Route::get('{slug}', [AdoptionController::class, 'show'])->name('adoption.show');
});

Route::group(['prefix' => 'apadrinhe'], function () {
    Route::get('/', [SponsorshipController::class, 'index'])->name('sponsorship.index');
    Route::get('{slug}', [SponsorshipController::class, 'show'])->name('sponsorship.show');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
