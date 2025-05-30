<?php

namespace App\Enums;

enum TahapVerifikasi: string
{
    case TU = 'tu';
    case KAPRODI = 'kaprodi';
    case KAJUR = 'kajur';
    case WALIDOSEN = 'walidosen';
}