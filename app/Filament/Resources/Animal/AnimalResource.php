<?php

namespace App\Filament\Resources\Animal;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Animal\Animal;
use App\Enums\Animal\SizeEnum;
use App\Enums\Animal\GenderEnum;
use App\Enums\Animal\HealthConditionEnum;
use App\Enums\Animal\SpecieEnum;
use App\Enums\Animal\StatusEnum;
use Filament\Resources\Resource;
use App\Enums\Animal\SociabilityEnum;
use App\Enums\Animal\TemperamentEnum;
use App\Enums\Animal\LocationTypeEnum;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Animal\AnimalResource\Pages;
use App\Filament\Resources\Animal\AnimalResource\RelationManagers;
use Carbon\Carbon;

class AnimalResource extends Resource 
{
    protected static ?string $model = Animal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Animal';

    protected static ?string $modelLabel = 'Animal';

    protected static ?string $navigationGroup = 'Animais';

    protected static ?string $navigationLabel = 'Lista de Animais';

    protected static ?string $pluralModelLabel = 'Animais';
    
    protected static ?string $slug = 'animais';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do animal')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')          
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state, string $operation) {
                                if (($get('slug') ?? '') !== Str::slug($old) || $operation === 'edit') {
                                    return;
                                }
        
                                $set('slug', Str::slug($state));
                            })
                            ->required()
                            ->markAsRequired(false)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->markAsRequired(false)
                            ->columnSpan(2),
                        Forms\Components\Select::make('specie')
                            ->label('Espécie')
                            ->options(SpecieEnum::class)
                            ->required(),
                        Forms\Components\Select::make('gender')
                            ->label('Sexo')
                            ->options(GenderEnum::class)
                            ->required()
                            ->markAsRequired(false),
                        Forms\Components\Select::make('size')
                            ->label('Porte')
                            ->options(SizeEnum::class)
                            ->required()
                            ->markAsRequired(false),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(StatusEnum::class)
                            ->required()
                            ->markAsRequired(false),
                        Forms\Components\Select::make('location_id')
                            ->label('Local')
                            ->relationship('location', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->markAsRequired(false)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('location_identification')
                            ->label('Identificação do Local')
                            ->hint('(opcional)')
                            ->columnSpan(2),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Data Nascimento')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->hint('(opcional)')
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('intake_date')
                            ->label('Data de Entrada no Abrigo')
                            ->native(false)
                            ->displayFormat('d/m/Y') 
                            ->hint('(opcional)')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_visible_on_site')
                            ->label('Exibir no site?')
                            ->required(),
                       
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Fotos')
                    ->schema([  
                        Forms\Components\SpatieMediaLibraryFileUpload::make('animal_images')
                            ->multiple()
                            ->reorderable()
                            ->responsiveImages()
                            ->conversion('thumbnail')
                            ->conversion('responsive')
                            ->panelLayout('grid')
                            ->collection('animals')
                            ->appendFiles()
                    ]),
              
                Forms\Components\Section::make('Sociabilidade')
                    ->schema([  
                        Forms\Components\Select::make('sociable_with_cats')
                            ->label('Sociável com gatos')
                            ->options(SociabilityEnum::class)
                            ->required(),
                        Forms\Components\Select::make('sociable_with_dogs')
                            ->label('Sociável com cachorros')
                            ->options(SociabilityEnum::class)
                            ->required(),
                        Forms\Components\Select::make('sociable_with_childrens')
                            ->label('Sociável com crianças')
                            ->options(SociabilityEnum::class)
                            ->required(),
                        Forms\Components\Select::make('temperaments')
                            ->label('Temperamentos')
                            ->multiple() 
                            ->options(TemperamentEnum::class)
                            ->searchable()
                            ->preload()
                            ->hint('(opcional)'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Outras informações')
                    ->schema([   
                        Forms\Components\Toggle::make('is_neutered')
                            ->label('Castrado?')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_adoption_ready')
                            ->label('Apto para adoção?')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('health_conditions')
                            ->label('Condições de saúde')
                            ->hint('(opcional)')
                            ->options(HealthConditionEnum::class)
                            ->searchable()
                            ->multiple()
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('special_needs')
                            ->label('Necessidades especiais')
                            ->hint('(opcional)')
                            ->placeholder('Nova necessidade')
                            ->separator(', ')
                            ->columnSpanFull(), 
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Descrição (visível no site)')
                    ->schema([   
                        Forms\Components\Textarea::make('short_description')
                            ->label('Descrição curta')
                            ->hint('(opcional)')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('full_description')
                            ->label('Descrição')
                            ->hint('(opcional)')
                            ->rows(10)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Anotações')
                    ->schema([  
                        Forms\Components\Textarea::make('notes')
                            ->label('Anotações (uso interno)')
                            ->hint('(opcional)')
                            ->rows(10)
                            ->columnSpanFull(),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('animal_images.0')
                    ->conversion('thumbnail')
                    ->limit(1),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Sexo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('specie')
                    ->label('Espécie')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location.is_volunteer')
                    ->label('Local')
                    ->badge()
                    ->color(fn (LocationTypeEnum $state): string => $state->getColor())
                    ->icon(fn (LocationTypeEnum $state): string => $state->getIcon()),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (StatusEnum $state): string => $state->getColor()),                    
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
            RelationManagers\ExpensesRelationManager::class,
            RelationManagers\SponsorshipRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimals::route('/'),
            'create' => Pages\CreateAnimal::route('/create'),
            'view' => Pages\ViewAnimal::route('/{record}'),
            'edit' => Pages\EditAnimal::route('/{record}/edit'),
        ];
    }



}
