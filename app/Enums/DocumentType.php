<?php

namespace App\Enums;

enum DocumentType: string
{
    case Ktp = 'ktp';
    case Sim = 'sim';
    case Other = 'other';
}
