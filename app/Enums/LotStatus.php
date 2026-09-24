<?php

namespace App\Enums;

enum LotStatus: string
{
    case ACTIVE = 'active';
    case IN_TRANSIT = 'in_transit';
    case STORED = 'stored';
    case TRANSFORMED = 'transformed';
    case SOLD = 'sold';
    case BLOCKED = 'blocked';
    case RECALLED = 'recalled';
    case EXPIRED = 'expired';
    case DESTROYED = 'destroyed';

    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }
}
