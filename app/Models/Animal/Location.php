<?php

namespace App\Models\Animal;

use App\Enums\Animal\LocationTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\Animal\LocationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'is_volunteer'
    ];

    protected $casts = [
        'is_volunteer' => LocationTypeEnum::class,
    ];

    public function animals(): HasMany  
    {
        return $this->hasMany(Animal::class);
    }
}
