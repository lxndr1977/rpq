<?php

namespace App\Enums\Animal;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasLabel, HasColor
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Adopted = 'adopted';
    case Dead = 'dead';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Ativo',
            self::Inactive => 'Inativo',
            self::Adopted => 'Adotado',
            self::Dead => 'Óbito',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'gray',
            self::Adopted => 'gray',
            self::Dead => 'gray',
        };
    }
}
