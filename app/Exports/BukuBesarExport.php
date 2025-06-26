<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BukuBesarExport implements FromView
{
    public $data, $mataKuliahs, $semester, $totalSemesters, $tahun_akademik, $program_studi, $tingkat_kelas, $kelasTerpilih, $tahun, $totalKolom;

    public function __construct($data, $mataKuliahs, $semester, $totalSemesters, $tahun_akademik, $program_studi, $tingkat_kelas, $kelasTerpilih, $tahun, $totalKolom)
    {
        $this->data = $data;
        $this->mataKuliahs = $mataKuliahs;
        $this->semester = $semester;
        $this->totalSemesters = $totalSemesters;
        $this->tahun_akademik = $tahun_akademik;
        $this->program_studi = $program_studi;
        $this->tingkat_kelas = $tingkat_kelas;
        $this->kelasTerpilih = $kelasTerpilih;
        $this->tahun = $tahun;
        $this->totalKolom = $totalKolom;
    }

    public function view(): View
    {
        return view('buku-besar-view.export-buku-besar', [
            'data' => $this->data,
            'mataKuliahs' => $this->mataKuliahs,
            'semester' => $this->semester,
            'totalSemesters' => $this->totalSemesters,
            'tahun_akademik' => $this->tahun_akademik,
            'program_studi' => $this->program_studi,
            'tingkat_kelas' => $this->tingkat_kelas,
            'kelasTerpilih' => $this->kelasTerpilih,
            'tahun' => $this->tahun,
            'totalKolom' => $this->totalKolom,
        ]);
    }
}
