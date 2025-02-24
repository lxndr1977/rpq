<?php

namespace App\Models\Animal;

use App\Enums\Animal\ExpenseTypeEnum;
use App\Enums\Animal\ExpenseStatusEnum;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Animal\ExpenseRecurrenceEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\Animal\ExpensesFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'amount',
        'start_date',
        'end_date',
        'recurrence_days',
        'payment_links',
        'total_sponsorship',
        'payment_link',
        'animal_id',
    ];

    protected $casts = [
        'recurrence_days' => ExpenseRecurrenceEnum::class,
        'type' => ExpenseTypeEnum::class,
        'status' => ExpenseStatusEnum::class,
        'payment_links' => 'array',

    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class, 'expense_id', 'id');

    }
    protected function status(): Attribute
    {
        return Attribute::get(function () {
            if (is_null($this->end_date)) {
                return ExpenseStatusEnum::Active;
            }

            return $this->end_date < now() ? ExpenseStatusEnum::Closed : ExpenseStatusEnum::Active;
        });
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('start_date') 
                ->orWhere('start_date', '<=', now())
                ->whereNull('end_date') 
                ->orWhere('end_date', '>=', now()); 
        });
    }

    public function scopeAvailable($query)
    {
        return $query->whereDoesntHave('sponsorships');

    }
}
