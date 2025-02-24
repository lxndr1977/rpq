<?php

namespace App\Filament\Resources\Animal\ExpenseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SponsorshipsRelationManager extends RelationManager
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
                    ->label('Apoiador')
                    ->relationship('sponsor', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Valor')
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
            ->recordTitleAttribute('sponsor.name')
            ->columns([
                Tables\Columns\TextColumn::make('sponsor.name')
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo?')
                    ->boolean(),
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
