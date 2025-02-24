<?php

namespace App\Filament\Resources\Animal;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Animal\Expense;
use Filament\Resources\Resource;
use App\Enums\Animal\ExpenseTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Animal\ExpenseRecurrenceEnum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Animal\ExpenseResource\Pages;
use App\Filament\Resources\Animal\ExpenseResource\RelationManagers;
use Filament\Forms\Components\Actions\Action;


class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Despesa';

    protected static ?string $modelLabel = 'Despesa';

    protected static ?string $navigationGroup = 'Animais';

    protected static ?string $navigationLabel = 'Despesas';

    protected static ?string $pluralModelLabel = 'Despesas';
    
    protected static ?string $slug = 'despesas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações da Despesa')
                    ->schema([
                        Forms\Components\Select::make('animal_id')
                            ->label('Animal')
                            ->required()
                            ->relationship('animal', 'name')
                            ->preload()
                            ->searchable()
                            ->columnSpan(3),
                        Forms\Components\Select::make('type')
                            ->label('Tipo de Despesa')
                            ->options(ExpenseTypeEnum::class)
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('amount')
                            ->label('Valor')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->prefix('R$'),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Data de Início')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Data de Término')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Forms\Components\Select::make('recurrence_days')
                            ->label('Recorrência')
                            ->options(ExpenseRecurrenceEnum::class),
                        // Forms\Components\Repeater::make('payment_links')
                        //     ->schema([
                        //         Forms\Components\TextInput::make('value')
                        //             ->numeric()
                        //             ->inputMode('decimal')
                        //             ->prefix('R$')
                        //             ->required()
                        //             ->markAsRequired(false)
                        //             ->label('Valor sugerido'),
                        //         Forms\Components\TextInput::make('link')
                        //             ->url()
                        //             ->required()
                        //             ->markAsRequired(false)
                        //             ->label('Link de pagamento'),
                        //     ])
                        //     ->label('Links de Pagamento')
                        //     ->addable()
                        //     ->deletable()
                        //     ->columns(2)    
                        //     ->deleteAction(
                        //         fn (Action $action) => $action->requiresConfirmation(),
                        //     )
                        //     ->columnSpanFull(),
                        Forms\Components\TextInput::make('link')
                        ->url()
                        ->required()
                        ->markAsRequired(false)
                        ->label('Link de pagamento'),
                        Forms\Components\TextInput::make('description')
                            ->label('Descrição')
                            ->columnSpanFull(),
                ])
                ->columns(4)
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
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('recurrence_days')
                    ->label('Recorrência')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            RelationManagers\SponsorshipsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
