<?php

namespace App\Enums;

enum PaymentType: string
{
    case Dp = 'dp';
    case Pelunasan = 'pelunasan';
    case Refund = 'refund';
}
