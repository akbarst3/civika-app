<?php

namespace App\Enums;

enum TahapVerifikasi: string
{
    case TU = 'TU';
    case KAPRODI = 'Kaprodi';
    case KAJUR = 'Kajur';
    case WALIDOSEN = 'WaliDosen';
}