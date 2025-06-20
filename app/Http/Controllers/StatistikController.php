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
        $mahasiswa = $this->getMahasiswaWithRelations();

        $grouped = $this->groupMahasiswaByAngkatanAndProdi($mahasiswa);

        $data = $this->calculateAverageIpsPerGroup($grouped);

        $angkatanPerProdi = $this->getAngkatanPerProdi($data);

        return view('statistik-view.statistik-main', [
            'data' => $data,
            'dataAngkatan' => $angkatanPerProdi
        ]);
    }

    private function getMahasiswaWithRelations()
    {
        return Mahasiswa::with(['kelas.prodi', 'indeksPrestasiSemester'])->get();
    }

    private function groupMahasiswaByAngkatanAndProdi($mahasiswa)
    {
        return $mahasiswa->groupBy(function ($item) {
            $angkatan = optional($item->kelas)->angkatan ?? 'unknown';
            $prodi = optional(optional($item->kelas)->prodi)->nama_prodi ?? 'unknown';
            return $angkatan . '_' . $prodi;
        });
    }

    private function calculateAverageIpsPerGroup($grouped)
    {
        return $grouped->map(function ($group, $key) {
            $avgIpsList = collect();
            $totalIps = 0;
            $totalMahasiswa = 0;

            foreach ($group as $mhs) {
                $ipsValues = $mhs->indeksPrestasiSemester->pluck('indeks_prestasi')->filter();
                $avgIpsMhs = $ipsValues->avg();

                if (!is_null($avgIpsMhs)) {
                    $avgIpsList->push($avgIpsMhs);
                    $totalIps += $avgIpsMhs;
                    $totalMahasiswa++;
                }
            }
            $ipkTertinggi = $avgIpsList->max();
            $ipkTerendah = $avgIpsList->min();
            $rataRataIpk  = $avgIpsList->avg();

            [$angkatan, $prodi] = explode('_', $key);

            return [
                'angkatan' => $angkatan,
                'prodi' => $prodi,
                'rata_rata_ips' => $rataRataIpk,
                'ipk_tertinggi' => $ipkTertinggi,
                'ipk_terendah' => $ipkTerendah
            ];
        })->sortBy(function ($item) {
            return (int) $item['angkatan'];
        })->values();
    }

    private function getAngkatanPerProdi($data)
    {
        return $data->groupBy('prodi')->map(function ($items) {
            return collect($items)->pluck('angkatan')->unique()->sort()->values()->all();
        });
    }
}
