<?php

namespace App\Models\Animal;

use App\Enums\Animal\ExpenseStatusEnum;
use Carbon\Carbon;
use App\Models\User;
use Spatie\Image\Enums\Fit;
use App\Enums\Animal\SizeEnum;
use App\Enums\Animal\GenderEnum;
use App\Enums\Animal\SpecieEnum;
use App\Enums\Animal\StatusEnum;
use App\Models\UserAnimalExpense;
use Spatie\MediaLibrary\HasMedia;
use App\Enums\Animal\SociabilityEnum;
use App\Enums\Animal\TemperamentEnum;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Animal\HealthConditionEnum;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Animal extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\Animal\AnimalFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'gender',
        'size',
        'specie',
        'intake_date',
        'birth_date',
        'short_description',
        'full_description',
        'sociable_with_cats',
        'sociable_with_dogs',
        'sociable_with_childrens',
        'temperaments',  
        'special_needs',
        'health_conditions',
        'is_neutered',
        'notes',
        'is_adoption_ready',
        'is_visible_on_site',
        'status',
        'location_id',
        'location_identification',
        'animal_images',
    ];

    protected $casts = [
        'gender' => GenderEnum::class, 
        'size' => SizeEnum::class,
        'specie' => SpecieEnum::class,
        'sociable_with_cats' => SociabilityEnum::class,
        'sociable_with_dogs' => SociabilityEnum::class,
        'sociable_with_childrens' => SociabilityEnum::class,
        'health_conditions' => 'array',
        'special_needs' => 'array',
        'status' => StatusEnum::class,
        'temperaments' => 'array',

    ];

    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'animal_id', 'id');
    }

    public function activeExpenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'animal_id', 'id')
            ->active();
    }

    public function availableExpenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'animal_id', 'id')
            ->active()
            ->available();
    }

    public function sponsors(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,              
            Sponsorship::class, 
            'expense_id',             
            'id',                     
            'id',                     
            'user_id'                 
        );
    }

    public function sponsorships(): HasManyThrough
    {
        return $this->hasManyThrough(
            Sponsorship::class, 
            Expense::class,           
            'animal_id',              
            'expense_id',             
            'id',                     
            'id'                      
        );
    }

    public function registerMediaConversions(?Media $media = null): void
    {

        $this->addMediaConversion('responsive')
            ->fit(Fit::Crop, 1280, 1280)
            ->format('webp')
            ->withResponsiveImages()
            ->nonQueued();

        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Crop, 50, 50)
            ->format('webp')
            ->nonQueued();
    }

    public function scopeOnlyActive($query)
    {
        return $query->where('status', StatusEnum::Active);
    }
    
    public function scopeAvailableForAdoption($query)
    {
        return $query->where('is_adoption_ready', true);
    }

    protected function temperamentLabels(): Attribute
    {
        return Attribute::make(
            get: fn () => collect($this->temperaments ?? [])
                ->map(fn($value) => TemperamentEnum::tryFrom($value)?->getLabel())
                ->filter()
                ->implode(', ')
        );
    }

    protected function healthConditionsLabels(): Attribute
    {
        return Attribute::make(
            get: fn () => collect($this->health_conditions ?? [])
                ->map(fn($value) => HealthConditionEnum::tryFrom($value)?->getLabel())
                ->filter()
                ->implode(', ')
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birth_date 
                ? Carbon::parse($this->birth_date)->age . ' anos' 
                : 'Não informado'
        );
    }

    protected function intakeYear(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->intake_date 
                ? Carbon::parse($this->intake_date)->year 
                : 'Não informado'
        );
    }

    protected function genderedName(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->gender === 'male' ? 'o ' : 'a ') . $this->name
        );
    }
}
