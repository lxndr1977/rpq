<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'whatsapp',
        'birth_date',
    ];

    public function expenses()
    {
        return $this->hasMany(UserAnimalExpense::class);
    }
}
