<?php

namespace App\Entity;

enum Role:string
{
    case ADMINISTRATOR = 'Administrator';
    case USER = 'User';
    public function label(): string
    {
        return match ($this) {
            self::ADMINISTRATOR => 'Administrator',
            self::USER => 'User',
        };
    }
}
