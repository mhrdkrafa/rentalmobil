<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Gateway = 'gateway';
    case ManualTransfer = 'manual_transfer';
    case Cash = 'cash';
}
