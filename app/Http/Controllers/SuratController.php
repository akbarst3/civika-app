<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SuratController extends Controller
{
    // Display dashboard surat
    public function dashboardSurat()
    {
        return view('surat-view.dashboard_surat');
    }

    // Display form for pengajuan rekomendasi
    public function dashboardSuratRokumendasi()
    {
        return view('surat-view.form-pengajuan-pengaju_rokumendasi');
    }

    // Display form for pengajuan beasiswa
    public function dashboardSuratBeasiswa()
    {
        return view('surat-view.form-pengajuan-pengaju_beasiswa');
    }
}