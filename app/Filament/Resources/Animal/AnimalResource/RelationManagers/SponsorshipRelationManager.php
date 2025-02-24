<?php

namespace App\Filament\Resources\Animal\AnimalResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Animal\Expense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SponsorshipRelationManager extends RelationManager
{
    protected static string $relationship = 'sponsorships';

    protected static ?string $label = 'Apadrinhamento';

    protected static ?string $pluralLabel = 'Apadrinhamentos';

    protected static ?string $modelLabel = 'Apadrinhamento';

    protected static ?string $pluralModelLabel = 'Apadrinhamentos';

    protected static ?string $title = 'Apadrinhamentos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Nome do Apoiador')
                    ->options(User::get()->pluck('name', 'id')->toArray())
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('expense_id')
                    ->label('Tipo da Depesa')
                    ->options(
                        Expense::where('animal_id', $this->getOwnerRecord()->id) 
                            ->get()
                            ->mapWithKeys(fn ($expense) => [
                                $expense->id => "{$expense->type->getLabel()} - R$ " . number_format($expense->amount, 2, ',', '.')
                            ])
                            ->toArray()
                    ),
                Forms\Components\TextInput::make('amount')
                    ->label('Valor do Apoio')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->prefix('R$'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo?')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nome do apoiador'),
                Tables\Columns\TextColumn::make('expense.type')
                    ->label('Tipo da Despesa'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
