<?php

namespace App\Http\Controllers;

use App\Imports\DataAkademikImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomImportExceptions\DataAlreadyExistsException; 
use App\Exceptions\CustomImportExceptions\InvalidExcelStructureException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportBukuBesarController extends Controller {
    public function showUploadForm()
    {
        return view('buku-besar-view.import-buku-besar');
    }

    public function importExcel(Request $request)
    {
        Log::info('ImportBukuBesarController@importExcel method dipanggil.');
        Log::debug('Data Request: ', $request->all());
        $validator = Validator::make($request->all(), [
            'excel_files' => 'required|array',
            'excel_files.*' => 'required|mimes:xlsx,xls,csv|max:20480' // Max 20MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan tidak valid. Pastikan file sesuai format dan ukuran maksimum.',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$request->hasFile('excel_files') || count($request->file('excel_files')) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang dipilih untuk diunggah.'
            ], 400);
        }

        $files = $request->file('excel_files');
        $results = [];
        $totalSheets = 0;
        $processedSheets = 0;

        foreach ($files as $file) {
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheetNames = $spreadsheet->getSheetNames();
                $totalSheets += count(array_intersect(['A', 'B', 'C'], $sheetNames));
            } catch (\Exception $e) {
                Log::error("Importer: Failed to load sheet names for file {$file->getClientOriginalName()}: " . $e->getMessage());
                continue;
            }
        }

        foreach ($files as $file) {
            $originalFileName = $file->getClientOriginalName();
            Log::info("Importer: Processing file: {$originalFileName}");

            $fileResult = [
                'fileName' => $originalFileName,
                'sheets' => []
            ];

            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $actualSheetNames = $spreadsheet->getSheetNames();
            } catch (\Exception $e) {
                $fileResult['sheets'][] = [
                    'sheetName' => 'N/A',
                    'success' => false,
                    'message' => "Gagal memuat file: " . $e->getMessage()
                ];
                $results[] = $fileResult;
                Log::error("Importer: Failed to load file {$originalFileName}: " . $e->getMessage());
                continue;
            }

            $expectedSheetNames = ['A', 'B', 'C'];

            foreach ($expectedSheetNames as $sheetName) {
                if (!in_array($sheetName, $actualSheetNames)) {
                    continue; // Skip if sheet doesn't exist
                }

                $sheetResult = [
                    'sheetName' => $sheetName,
                    'success' => false,
                    'message' => 'Terjadi kesalahan tidak diketahui saat memproses sheet.'
                ];

                try {
                    // Process one sheet at a time
                    Excel::import(new DataAkademikImport($sheetName), $file);
                    $sheetResult['success'] = true;
                    $sheetResult['message'] = "Sheet {$sheetName} berhasil diimpor.";
                    Log::info("Importer: Sheet '{$sheetName}' in file '{$originalFileName}' berhasil diimpor.");
                } catch (DataAlreadyExistsException $e) {
                    $sheetResult['message'] = "Gagal impor sheet {$sheetName}: Data sudah ada. Detail: " . $e->getMessage();
                    Log::warning("Importer: DataAlreadyExistsException for sheet {$sheetName} in {$originalFileName}: " . $e->getMessage());
                } catch (InvalidExcelStructureException $e) {
                    $sheetResult['message'] = "Gagal impor sheet {$sheetName}: Struktur Excel tidak sesuai. Detail: " . $e->getMessage();
                    Log::warning("Importer: InvalidExcelStructureException for sheet {$sheetName} in {$originalFileName}: " . $e->getMessage());
                } catch (ValidationException $e) {
                    $failures = $e->failures();
                    $errorString = "Validasi gagal untuk sheet {$sheetName} di file '{$originalFileName}': ";
                    $detailedErrors = [];
                    foreach ($failures as $failure) {
                        $attribute = $failure->attribute();
                        $value = is_array($failure->values()) && isset($failure->values()[$attribute]) ? $failure->values()[$attribute] : (!is_array($failure->values()) ? $failure->values() : '');
                        $detailedErrors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors()) . " (Nilai: {$value}). ";
                    }
                    $sheetResult['message'] = "Gagal impor karena struktur Excel tidak sesuai (kesalahan validasi data). Detail: " . implode(" ", $detailedErrors);
                    Log::warning("Importer: ValidationException for sheet {$sheetName} in {$originalFileName}: " . $sheetResult['message']);
                } catch (\Maatwebsite\Excel\Exceptions\SheetNotFoundException $e) {
                    $sheetResult['message'] = "Gagal impor: Sheet {$sheetName} tidak ditemukan.";
                    Log::error("Importer: SheetNotFoundException for sheet {$sheetName} in {$originalFileName}: " . $e->getMessage());
                } catch (\Exception $e) {
                    $sheetResult['message'] = "Gagal impor sheet {$sheetName}: Kesalahan tidak terduga. Detail: " . $e->getMessage();
                    Log::error("Importer: Generic Error importing sheet {$sheetName} in {$originalFileName}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                }

                $fileResult['sheets'][] = $sheetResult;
                $processedSheets++;
            }

            $results[] = $fileResult;
        }

        return response()->json([
            'success' => true,
            'totalSheets' => $totalSheets,
            'processedSheets' => $processedSheets,
            'results' => $results
        ]);
    }
}