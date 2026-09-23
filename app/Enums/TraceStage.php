<?php

namespace App\Enums;

enum TraceStage: string
{
    case PRODUCTION = 'production';
    case TRANSFORMATION = 'transformation';
    case PACKAGING = 'packaging';
    case STORAGE = 'storage';
    case TRANSPORT = 'transport';
    case DISTRIBUTION = 'distribution';
    case SALE = 'sale';

    public static function values(): array
    {
        return array_map(fn (self $stage) => $stage->value, self::cases());
    }
}
