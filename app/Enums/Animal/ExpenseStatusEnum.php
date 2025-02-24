<?php

namespace App\Enums\Animal;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ExpenseStatusEnum: string implements HasLabel, HasColor
{
    case Active = 'active';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Ativo',
            self::Closed => 'Encerrada',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Active => 'success',
            self::Closed => 'gray',
        };
    }
}
