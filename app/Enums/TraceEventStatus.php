<?php

namespace App\Enums;

enum TraceEventStatus: string
{
    case PENDING = 'pending';
    case VALIDATED = 'validated';
    case REJECTED = 'rejected';
    case BLOCKED = 'blocked';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }
}
