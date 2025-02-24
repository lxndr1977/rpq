<?php

namespace App\Filament\Resources\Animal;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Animal\Expense;
use Filament\Resources\Resource;
use App\Models\Animal\Sponsorship;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope; 
use App\Filament\Resources\Animal\SponsorshipResource\Pages;
use App\Filament\Resources\Animal\SponsorshipResource\RelationManagers;


class SponsorshipResource extends Resource
{
    protected static ?string $model = Sponsorship::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Apadrinhamento';

    protected static ?string $modelLabel = 'Apadrinhamento';

    protected static ?string $navigationGroup = 'Animais';

    protected static ?string $navigationLabel = 'Apadrinhamentos';

    protected static ?string $pluralModelLabel = 'Apadrinhamentos';
    
    protected static ?string $slug = 'Apadrinhamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Apoiador')
                    ->relationship('user', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('animal_id')
                    ->relationship('animal', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->live()            
                    ->afterStateUpdated(function ($state, $set) {
                        $set('expense_id', null);  
                    }),
                Forms\Components\Select::make('expense_id')
                    ->options(function (Get $get) {
                        return Expense::query()
                            ->where('animal_id', $get('animal_id')) 
                            ->get() 
                            ->mapWithKeys(fn ($expense) => [
                                $expense->id => "{$expense->type->getLabel()} - R$ " . number_format($expense->amount, 2, ',', '.')
                            ])
                            ->toArray();
                    })
                    ->live(),
                Forms\Components\TextInput::make('amount')
                    ->label('Valor')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo?')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('animal.name')
                    ->label('Animal')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Apoiador')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expense.type')
                    ->label('Despesa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expense.recurrence_days')
                    ->label('Frequencia')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSponsorships::route('/'),
            'create' => Pages\CreateSponsorship::route('/create'),
            'view' => Pages\ViewSponsorship::route('/{record}'),
            'edit' => Pages\EditSponsorship::route('/{record}/edit'),
        ];
    }
}
