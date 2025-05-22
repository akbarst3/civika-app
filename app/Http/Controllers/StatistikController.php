<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class StatistikController extends Controller
{
    public function index(): View
    {
        $data = [
            'title' => 'Data Statistik - Visualisasi Data',
            'programs' => $this->getAvailablePrograms(),
            'years' => $this->getAvailableYears(),
        ];

        return view('statistik-view.statistik-main', $data);
    }

    public function getData(Request $request): JsonResponse
    {
        $program = $request->input('program', 'D3');
        $startYear = $request->input('start_year', 2015);
        $endYear = $request->input('end_year', 2019);
        $semester = $request->input('semester', 'all');

        $data = $this->getStatistikData($program, $startYear, $endYear, $semester);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Data berhasil dimuat'
        ]);
    }

    public function export(Request $request): JsonResponse
    {
        $format = $request->input('format', 'excel');
        $program = $request->input('program', 'D3');
        
        try {
            $result = $this->exportData($format, $program);
            
            return response()->json([
                'success' => true,
                'message' => "Data berhasil diekspor ke format {$format}",
                'download_url' => $result['url'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query', '');
        
        if (strlen($query) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Query pencarian minimal 3 karakter'
            ], 400);
        }

        $results = $this->searchData($query);

        return response()->json([
            'success' => true,
            'data' => $results,
            'total' => count($results),
            'message' => count($results) > 0 ? 'Data ditemukan' : 'Tidak ada data yang cocok'
        ]);
    }

    public function getTrendAnalysis(Request $request): JsonResponse
    {
        $program = $request->input('program', 'D3');
        $analysis = $this->calculateTrendAnalysis($program);

        return response()->json([
            'success' => true,
            'data' => $analysis,
            'message' => 'Analisis trend berhasil dibuat'
        ]);
    }

    public function getComparison(): JsonResponse
    {
        $comparison = $this->generateComparisonData();

        return response()->json([
            'success' => true,
            'data' => $comparison,
            'message' => 'Data perbandingan berhasil dimuat'
        ]);
    }

    private function getAvailablePrograms(): array
    {
        return [
            'D3' => [
                'code' => 'D3',
                'name' => 'D3 - Teknik Informatika',
                'description' => 'Diploma III - Program studi 3 tahun dengan fokus pada praktik dan aplikasi teknik informatika',
                'duration' => 3,
                'total_sks' => 110
            ],
            'D4' => [
                'code' => 'D4',
                'name' => 'D4 - Teknik Informatika',
                'description' => 'Diploma IV - Program studi 4 tahun setara S1 dengan fokus pada teknologi terapan',
                'duration' => 4,
                'total_sks' => 144
            ]
        ];
    }

    private function getAvailableYears(): array
    {
        return range(2015, 2019);
    }

    private function getStatistikData(string $program, int $startYear, int $endYear, string $semester): array
    {
        // Simulasi database query
        $baseData = $this->getBaseStatistikData();
        
        $filteredData = $baseData[$program] ?? $baseData['D3'];
        
        if ($startYear !== 2015 || $endYear !== 2019) {
            $filteredData = $this->filterDataByYear($filteredData, $startYear, $endYear);
        }
        
        if ($semester !== 'all') {
            $filteredData = $this->filterDataBySemester($filteredData, $semester);
        }

        return $filteredData;
    }

    private function getBaseStatistikData(): array
    {
        return [
            'D3' => [
                'program_info' => [
                    'code' => 'D3',
                    'name' => 'D3 - Teknik Komputer dan Informatika',
                    'description' => 'Program Diploma III dengan fokus praktik dan aplikasi'
                ],
                'chart_data' => [
                    'labels' => ['2015', '2016', '2017', '2018', '2019'],
                    'ipk_data' => [3.2, 3.3, 3.4, 3.1, 3.5],
                    'mahasiswa_data' => [220, 235, 248, 255, 260]
                ],
                'summary' => [
                    'total_mahasiswa' => 1250,
                    'mahasiswa_aktif' => 980,
                    'rata_ipk' => 3.28,
                    'tingkat_kelulusan' => 92,
                    'dosen_tetap' => 25,
                    'dosen_tidak_tetap' => 8,
                    'laboratorium' => 6,
                    'kerjasama_industri' => 45
                ],
                'characteristics' => [
                    'Fokus pada praktik dan aplikasi',
                    'Durasi 3 tahun (6 semester)',
                    'Magang industri wajib',
                    'Tugas Akhir berupa proyek aplikatif'
                ],
                'trend' => [
                    'direction' => 'naik',
                    'percentage' => 9.4,
                    'analysis' => 'Program D3 menunjukkan peningkatan IPK yang konsisten'
                ]
            ],
            'D4' => [
                'program_info' => [
                    'code' => 'D4',
                    'name' => 'D4 - Teknik Komputer dan Informatika',
                    'description' => 'Program Diploma IV setara S1 dengan fokus teknologi terapan'
                ],
                'chart_data' => [
                    'labels' => ['2015', '2016', '2017', '2018', '2019'],
                    'ipk_data' => [3.4, 3.5, 3.3, 3.2, 3.6],
                    'mahasiswa_data' => [150, 160, 165, 170, 180]
                ],
                'summary' => [
                    'total_mahasiswa' => 850,
                    'mahasiswa_aktif' => 720,
                    'rata_ipk' => 3.40,
                    'tingkat_kelulusan' => 95,
                    'dosen_tetap' => 30,
                    'dosen_tidak_tetap' => 5,
                    'laboratorium' => 8,
                    'kerjasama_industri' => 52
                ],
                'characteristics' => [
                    'Program setara dengan S1',
                    'Durasi 4 tahun (8 semester)',
                    'Penelitian dan pengembangan',
                    'Tugas Akhir berupa penelitian aplikatif'
                ],
                'trend' => [
                    'direction' => 'naik',
                    'percentage' => 5.9,
                    'analysis' => 'Program D4 menunjukkan stabilitas dengan tren positif'
                ]
            ]
        ];
    }

    private function filterDataByYear(array $data, int $startYear, int $endYear): array
    {
        $allYears = $data['chart_data']['labels'];
        $startIndex = array_search($startYear, $allYears);
        $endIndex = array_search($endYear, $allYears);
        
        if ($startIndex !== false && $endIndex !== false) {
            $data['chart_data']['labels'] = array_slice($allYears, $startIndex, $endIndex - $startIndex + 1);
            $data['chart_data']['ipk_data'] = array_slice($data['chart_data']['ipk_data'], $startIndex, $endIndex - $startIndex + 1);
            $data['chart_data']['mahasiswa_data'] = array_slice($data['chart_data']['mahasiswa_data'], $startIndex, $endIndex - $startIndex + 1);
        }
        
        return $data;
    }

    private function filterDataBySemester(array $data, string $semester): array
    {
        // Simulasi filter semester
        if ($semester === 'ganjil') {
            // Filter per semester
            $data['summary']['note'] = 'Data semester ganjil';
        } elseif ($semester === 'genap') {
            $data['summary']['note'] = 'Data semester genap';
        }
        
        return $data;
    }

    private function exportData(string $format, string $program): array
    {
        $data = $this->getStatistikData($program, 2015, 2019, 'all');
        $timestamp = now()->format('YmdHis');
        $filename = "statistik_{$program}_{$timestamp}";
        
        switch ($format) {
            case 'excel':
                return $this->exportToExcel($data, $filename);
            case 'pdf':
                return $this->exportToPDF($data, $filename);
            case 'csv':
                return $this->exportToCSV($data, $filename);
            default:
                throw new \Exception('Format tidak didukung');
        }
    }

    private function exportToExcel(array $data, string $filename): array
    {
        return [
            'success' => true,
            'filename' => $filename . '.xlsx',
            'url' => '/downloads/' . $filename . '.xlsx',
            'size' => '2.5 MB'
        ];
    }

    private function exportToPDF(array $data, string $filename): array
    {
        return [
            'success' => true,
            'filename' => $filename . '.pdf',
            'url' => '/downloads/' . $filename . '.pdf',
            'size' => '1.8 MB'
        ];
    }

    private function exportToCSV(array $data, string $filename): array
    {
        // Simulate CSV export
        $csvData = $this->convertToCSV($data);
        
        return [
            'success' => true,
            'filename' => $filename . '.csv',
            'url' => '/downloads/' . $filename . '.csv',
            'size' => '50 KB',
            'data' => $csvData
        ];
    }

    private function convertToCSV(array $data): string
    {
        $output = "Tahun,IPK,Jumlah Mahasiswa\n";
        
        foreach ($data['chart_data']['labels'] as $index => $year) {
            $ipk = $data['chart_data']['ipk_data'][$index] ?? 0;
            $mahasiswa = $data['chart_data']['mahasiswa_data'][$index] ?? 0;
            $output .= "{$year},{$ipk},{$mahasiswa}\n";
        }
        
        return $output;
    }

    private function searchData(string $query): array
    {
        $allData = $this->getBaseStatistikData();
        $results = [];
        
        foreach ($allData as $program => $data) {
            // Cari di nama program 
            if (stripos($data['program_info']['name'], $query) !== false) {
                $results[] = [
                    'type' => 'program',
                    'program' => $program,
                    'title' => $data['program_info']['name'],
                    'description' => $data['program_info']['description'],
                    'relevance' => 'high'
                ];
            }
            
            // Cari di karakteristik
            foreach ($data['characteristics'] as $characteristic) {
                if (stripos($characteristic, $query) !== false) {
                    $results[] = [
                        'type' => 'characteristic',
                        'program' => $program,
                        'title' => $characteristic,
                        'relevance' => 'medium'
                    ];
                }
            }
        }
        
        return $results;
    }

    private function calculateTrendAnalysis(string $program): array
    {
        $data = $this->getBaseStatistikData()[$program] ?? $this->getBaseStatistikData()['D3'];
        $ipkData = $data['chart_data']['ipk_data'];
        
        if (count($ipkData) < 2) {
            return [
                'direction' => 'stabil',
                'percentage' => 0,
                'analysis' => 'Data tidak cukup untuk analisis trend'
            ];
        }
        
        $firstValue = $ipkData[0];
        $lastValue = end($ipkData);
        $trend = (($lastValue - $firstValue) / $firstValue) * 100;
        
        $direction = $trend > 0 ? 'naik' : ($trend < 0 ? 'turun' : 'stabil');
        
        return [
            'direction' => $direction,
            'percentage' => abs($trend),
            'analysis' => $this->generateTrendAnalysisText($direction, abs($trend), $program),
            'recommendation' => $this->generateRecommendation($direction, $program)
        ];
    }

    private function generateTrendAnalysisText(string $direction, float $percentage, string $program): string
    {
        $text = "Program {$program} menunjukkan {$direction} IPK sebesar " . number_format($percentage, 1) . "% dalam 5 tahun terakhir. ";
        
        if ($direction === 'naik' && $percentage > 5) {
            $text .= "Ini menunjukkan peningkatan kualitas akademik yang signifikan.";
        } elseif ($direction === 'turun' && $percentage > 5) {
            $text .= "Perlu evaluasi dan perbaikan sistem pembelajaran.";
        } else {
            $text .= "IPK relatif stabil dengan fluktuasi minimal.";
        }
        
        return $text;
    }

    private function generateRecommendation(string $direction, string $program): array
    {
        $recommendations = [];
        
        if ($direction === 'naik') {
            $recommendations = [
                'Pertahankan kualitas pembelajaran yang sudah baik',
                'Tingkatkan fasilitas pendukung akademik',
                'Ekspansi program kerjasama industri',
                'Evaluasi berkala untuk mempertahankan tren positif'
            ];
        } elseif ($direction === 'turun') {
            $recommendations = [
                'Evaluasi metode pembelajaran dan kurikulum',
                'Peningkatan bimbingan akademik mahasiswa',
                'Review kompetensi dan kualifikasi dosen',
                'Perbaikan sarana dan prasarana pembelajaran'
            ];
        } else {
            $recommendations = [
                'Pertahankan stabilitas yang sudah ada',
                'Identifikasi peluang peningkatan kualitas',
                'Benchmarking dengan program sejenis',
                'Inovasi dalam metode pembelajaran'
            ];
        }
        
        return $recommendations;
    }

    private function generateComparisonData(): array
    {
        $d3Data = $this->getBaseStatistikData()['D3'];
        $d4Data = $this->getBaseStatistikData()['D4'];
        
        return [
            'categories' => [
                [
                    'name' => 'Total Mahasiswa',
                    'd3_value' => $d3Data['summary']['total_mahasiswa'],
                    'd4_value' => $d4Data['summary']['total_mahasiswa'],
                    'difference' => $d3Data['summary']['total_mahasiswa'] - $d4Data['summary']['total_mahasiswa'],
                    'unit' => 'orang'
                ],
                [
                    'name' => 'Mahasiswa Aktif',
                    'd3_value' => $d3Data['summary']['mahasiswa_aktif'],
                    'd4_value' => $d4Data['summary']['mahasiswa_aktif'],
                    'difference' => $d3Data['summary']['mahasiswa_aktif'] - $d4Data['summary']['mahasiswa_aktif'],
                    'unit' => 'orang'
                ],
                [
                    'name' => 'Rata-rata IPK',
                    'd3_value' => $d3Data['summary']['rata_ipk'],
                    'd4_value' => $d4Data['summary']['rata_ipk'],
                    'difference' => $d3Data['summary']['rata_ipk'] - $d4Data['summary']['rata_ipk'],
                    'unit' => ''
                ],
                [
                    'name' => 'Tingkat Kelulusan',
                    'd3_value' => $d3Data['summary']['tingkat_kelulusan'],
                    'd4_value' => $d4Data['summary']['tingkat_kelulusan'],
                    'difference' => $d3Data['summary']['tingkat_kelulusan'] - $d4Data['summary']['tingkat_kelulusan'],
                    'unit' => '%'
                ]
            ],
            'summary' => [
                'total_comparison' => 4,
                'better_d3' => 2,
                'better_d4' => 2,
                'equal' => 0
            ]
        ];
    }
}
