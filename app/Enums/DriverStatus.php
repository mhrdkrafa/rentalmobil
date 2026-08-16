<?php

namespace App\Enums;

enum DriverStatus: string
{
    case Available = 'available';
    case OnDuty = 'on_duty';
    case Off = 'off';
}
