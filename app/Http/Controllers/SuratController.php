<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        return view('surat-view/form-pengajuan-pengaju');
    }
}
