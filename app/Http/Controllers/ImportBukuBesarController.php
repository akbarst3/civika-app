<?php

namespace App\Http\Controllers;

use App\Imports\DataAkademikImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException; // Jika menggunakan validasi Maatwebsite
use Illuminate\Support\Facades\Log;

class ImportBukuBesarController extends Controller
{
    public function showUploadForm()
    {
        return view('buku-besar-view/upload_form'); // Pastikan nama view sesuai
    }
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_files' => 'required|array',
            'excel_files.*' => 'required|mimes:xlsx,xls,csv|max:20480'
        ]);

        $successMessages = [];
        $errorMessages = [];

        if ($request->hasFile('excel_files')) {
            foreach ($request->file('excel_files') as $file) {
                $originalFileName = $file->getClientOriginalName();
                $tempPath = $file->getRealPath();
                Log::debug("Importer: Processing file: {$originalFileName}, Temp path: {$tempPath}");

                if (!file_exists($tempPath)) {
                    $errorMessages[] = "File '{$originalFileName}' tidak ditemukan di path sementara: {$tempPath}";
                    continue;
                }

                try {
                    Excel::import(new DataAkademikImport(), $file);
                    $successMessages[] = "File '{$originalFileName}' berhasil diimpor.";
                } catch (ValidationException $e) {
                    $failures = $e->failures();
                    $errorString = "Validasi gagal untuk file '{$originalFileName}': ";
                    foreach ($failures as $failure) {
                        $errorString .= "Baris {$failure->row()}: " . implode(', ', $failure->errors()) . " (Nilai: {$failure->values()[$failure->attribute()]}). ";
                    }
                    $errorMessages[] = $errorString;
                } catch (\Exception $e) {
                    $errorMessages[] = "Gagal mengimpor file '{$originalFileName}': " . $e->getMessage();
                    Log::error("Importer: Error importing {$originalFileName}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                }
            }
        } else {
            return redirect()->back()->with('error', 'Tidak ada file yang dipilih.');
        }

        $redirect = redirect()->route('excel.upload.form');
        if (!empty($successMessages)) {
            $redirect->with('success_messages', $successMessages);
        }
        if (!empty($errorMessages)) {
            $redirect->with('error_messages', $errorMessages);
        }

        return $redirect;
    }
}