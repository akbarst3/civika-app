<?php

namespace App\Enums;

enum StatusSurat: string
{
    case DRAFT = 'draft';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case DIPROSES = 'diproses';
}