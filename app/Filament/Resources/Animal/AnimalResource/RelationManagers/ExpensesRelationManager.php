<?php

namespace App\Filament\Resources\Animal\AnimalResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Support\Enums\IconSize;
use App\Enums\Animal\ExpenseTypeEnum;
use App\Enums\Animal\ExpenseStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Animal\ExpenseRecurrenceEnum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Actions\Action;

class ExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';

    protected static ?string $label = 'Despesa';

    protected static ?string $pluralLabel = 'Despesas';

    protected static ?string $modelLabel = 'Depsesa';

    protected static ?string $pluralModelLabel = 'Despesas';

    protected static ?string $title = 'Despesas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->required()
                    ->options(ExpenseTypeEnum::class),
                Forms\Components\TextInput::make('description')
                    ->label('Descrição'),                
                Forms\Components\TextInput::make('amount')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->prefix('R$'),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Inicia em')
                    ->locale('pt_BR'),   
                Forms\Components\DatePicker::make('end_date')
                    ->label('Termina em'), 
                Forms\Components\Select::make('recurrence_days')
                    ->label('Recorrência')
                    ->options(ExpenseRecurrenceEnum::class)
                    ->required(),       
                    // Forms\Components\Repeater::make('payment_links')
                    // ->schema([
                    //     Forms\Components\TextInput::make('value')
                    //         ->numeric()
                    //         ->inputMode('decimal')
                    //         ->prefix('R$')
                    //         ->required()
                    //         ->label('Valor sugerido'),
                    //     Forms\Components\TextInput::make('link')
                    //         ->url()
                    //         ->required()
                    //         ->label('Link de pagamento'),
                    // ])
                    // ->label('Links de Pagamento')
                    // ->addable()
                    // ->deletable()
                    // ->columns(2)    
                    // ->deleteAction(
                    //     fn (Action $action) => $action->requiresConfirmation(),
                    // )
                    // ->columnSpanFull(),            
                    Forms\Components\TextInput::make('payment_link')
                    ->url()
                    ->required()
                    ->markAsRequired(false)
                    ->label('Link de pagamento'),                                                         
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('recurrence_days')
                    ->label('Recorrência'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_sponsorship')
                    ->label('Apadrinhado')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Situação')        
                    ->badge()                
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('status')
                //     ->label('Status')
                //     ->options(ExpenseStatusEnum::class)
                //     ->default(ExpenseStatusEnum::Active->value),            
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->iconSize(IconSize::Small),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->iconSize(IconSize::Small),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
