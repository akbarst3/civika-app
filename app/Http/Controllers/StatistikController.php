<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class StatistikController extends Controller
{    
    public function index(): View
    {
        $mahasiswa = Mahasiswa::with(['kelas.prodi', 'indeksPrestasiSemester'])->get();

        $grouped = $mahasiswa->groupBy(function ($item) {
            $angkatan = optional($item->kelas)->angkatan ?? 'unknown';
            $prodi = optional(optional($item->kelas)->prodi)->nama_prodi ?? 'unknown';
            return $angkatan . '_' . $prodi;
        });

        $data = $grouped->map(function ($group, $key) {
            $totalIps = 0;
            $totalMahasiswa = 0;

            foreach ($group as $mhs) {
                $ipsValues = $mhs->indeksPrestasiSemester->pluck('indeks_prestasi')->filter();
                $avgIpsMhs = $ipsValues->avg();

                if (!is_null($avgIpsMhs)) {
                    $totalIps += $avgIpsMhs;
                    $totalMahasiswa++;
                }
            }

            [$angkatan, $prodi] = explode('_', $key);

            return [
                'angkatan' => $angkatan,
                'prodi' => $prodi,
                'rata_rata_ips' => $totalMahasiswa > 0 ? round($totalIps / $totalMahasiswa, 2) : null,
            ];
        })->sortBy(function ($item) {
            return (int) $item['angkatan'];
        })
        ->values();

        $angkatanPerProdi = $data->groupBy('prodi')->map(function ($items) {
            return collect($items)->pluck('angkatan')->unique()->sort()->values()->all();
        });

        return view('statistik-view.statistik-main', ['data' => $data, 'dataAngkatan' => $angkatanPerProdi]);
    }
}
