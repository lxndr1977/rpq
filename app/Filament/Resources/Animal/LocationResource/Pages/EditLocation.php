<?php

namespace App\Filament\Resources\Animal\LocationResource\Pages;

use Filament\Actions;
use App\Models\Animal\Location;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Animal\LocationResource;

class EditLocation extends EditRecord
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function (DeleteAction $action, Location $record) {
                    if ($record->animals()->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('A exclusão falhou!')
                            ->body('O local possui animais relacionados.')
                            ->persistent()
                            ->send();

                            $action->cancel();
                    }
                }),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
