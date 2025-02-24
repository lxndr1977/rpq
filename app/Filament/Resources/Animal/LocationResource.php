<?php

namespace App\Filament\Resources\Animal;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Animal\Location;
use Filament\Resources\Resource;
use App\Enums\Animal\LocationTypeEnum;
use Filament\Support\Enums\ActionSize;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Animal\LocationResource\Pages;
use App\Filament\Resources\Animal\LocationResource\RelationManagers;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Local';

    protected static ?string $modelLabel = 'Local';

    protected static ?string $navigationGroup = 'Animais';

    protected static ?string $navigationLabel = 'Locais';

    protected static ?string $pluralModelLabel = 'Locais';
    
    protected static ?string $slug = 'locais';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->maxLength(255)
                    ->email(),
                Forms\Components\TextInput::make('whatsapp')
                    ->label('Whatsapp'),
                Forms\Components\ToggleButtons::make('is_volunteer')
                    ->label('Tipo')
                    ->options(LocationTypeEnum::class)
                    ->colors(LocationTypeEnum::getColors())
                    ->icons(LocationTypeEnum::getIcons())
                    ->grouped()
                    ->inline(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('Whatsapp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_volunteer')
                    ->label('Voluntário?')
                    ->badge()
                    ->color(fn (LocationTypeEnum $state): string => $state->getColor())
                    ->icon(fn (LocationTypeEnum $state): string => $state->getIcon()),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_volunteer')
                    ->label('Tipo')
                    ->options(LocationTypeEnum::class),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->before(function (Tables\Actions\DeleteAction $action, Location $record) {
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
                ])
                ->size(ActionSize::Small)
                ->tooltip('Ações')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'view' => Pages\ViewLocation::route('/{record}'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
