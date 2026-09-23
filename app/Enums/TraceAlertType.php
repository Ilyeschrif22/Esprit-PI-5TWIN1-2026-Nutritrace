<?php

namespace App\Enums;

enum TraceAlertType: string
{
    case TEMPERATURE = 'temperature';
    case QUANTITY = 'quantity';
    case LOCATION = 'location';
    case TIMELINE = 'timeline';
    case STORAGE = 'storage';
    case TRANSPORT = 'transport';
    case STATUS = 'status';
    case TRACEABILITY = 'traceability';
}
