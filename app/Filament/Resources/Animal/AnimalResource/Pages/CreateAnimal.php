<?php

namespace App\Filament\Resources\Animal\AnimalResource\Pages;

use App\Filament\Resources\Animal\AnimalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnimal extends CreateRecord
{
    protected static string $resource = AnimalResource::class;
}
