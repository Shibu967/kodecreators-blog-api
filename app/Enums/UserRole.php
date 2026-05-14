<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MAINTAINER = 'maintainer';
    case USER = 'user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return [
            self::ADMIN->value => 'Admin',
            self::MAINTAINER->value => 'Maintainer',
            self::USER->value => 'User',
        ];
    }
}
