<?php

namespace App\Filament\Resources\Animal\ExpenseResource\Pages;

use App\Filament\Resources\Animal\ExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
}
