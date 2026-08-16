<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case DpPaid = 'dp_paid';
    case PaidFull = 'paid_full';
    case Refunded = 'refunded';
}
