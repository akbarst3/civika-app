<?php

namespace App\Enums;

enum TahapVerifikasi: string
{
    case TU = 'TU';
    case KAPRODI = 'kaprodi';
    case KAJUR = 'kajur';
    case WALIDOSEN = 'wali dosen';
}