<?php

namespace App\Enums;

enum FuelType: string
{
    case Bensin = 'bensin';
    case Diesel = 'diesel';
    case Listrik = 'listrik';
    case Hybrid = 'hybrid';
}
