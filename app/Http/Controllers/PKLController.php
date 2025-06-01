<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\KpPklImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class PKLController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'angkatan' => 'required|integer|min:1900|max:' . date('Y'),
            'prodi' => 'required|exists:prodi,kode_prodi',
        ]);

        try {
            Excel::import(new KpPklImport($request->angkatan, $request->prodi), $request->file('file'));
            return redirect()->back()->with('success', 'Data KP/PKL imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing KP/PKL data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to import KP/PKL data: ' . $e->getMessage());
        }
    }
}
