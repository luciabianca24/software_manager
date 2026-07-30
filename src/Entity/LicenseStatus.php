<?php

namespace App\Entity;

enum LicenseStatus : string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case REVOKED = 'revoked';
    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::REVOKED => 'Revoked',
            self::EXPIRED => 'Expired',
        };
    }
}
