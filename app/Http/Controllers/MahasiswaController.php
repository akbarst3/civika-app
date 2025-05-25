<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\IndeksPrestasiSemester;
use App\Models\Prodi;
use Illuminate\Support\Facades\Log;

class MahasiswaController extends Controller
{
    public function bukuBesar(Request $request)
    {
        $tahun = $request->input('tahun', '2025');
        $semester = $request->input('semester', 3);
        $kelas = $request->input('kelas', 'C');
        $program_studi = $request->input('program_studi', 1);
        $tahun_akademik = $tahun . '/' . ($tahun + 1);

        // data prodi untuk dropdown
        $prodis = Prodi::all();

        // data matkul untuk dropdown
        $mataKuliahs = MataKuliah::whereIn('kode_matkul', function ($query) use ($semester) {
            $query->select('kode_matkul')->from('nilai')->where('semester_ke', $semester);
        })->get();

        Log::info('MataKuliahs:', ['count' => $mataKuliahs->count(), 'data' => $mataKuliahs->pluck('kode_matkul')->toArray()]);

        $mahasiswas = Mahasiswa::with([
            'kelas.prodi',
            'nilai' => function ($query) {
                $query->with(['mataKuliah', 'dosen']);
            },
            'absensi' => function ($query) use ($semester) {
                $query->where('semester', $semester);
            },
            'indeksPrestasiSemester' => function ($query) use ($semester) {
                $query->whereIn('semester', [$semester, $semester - 1]);
            },
        ])->get();

        $totalSemesters = IndeksPrestasiSemester::select('semester')->distinct()->count();

        $data = $mahasiswas->map(function ($mhs, $index) use ($semester) {
            $nilaiSemester = $mhs->nilai->where('semester_ke', $semester);

            $totalSks = $nilaiSemester->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $jumlah_d = $mhs->indeksPrestasiSemester->firstWhere('semester', $semester)->jumlah_d ?? 0;
            $totalSksD = $mhs->indeksPrestasiSemester->sum('jumlah_d');
            $ipSekarang = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->indeks_prestasi ?? 0;
            $ipLalu = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester - 1))->indeks_prestasi ?? 0;
            $nilai_bobot = optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->nilai_bobot ?? 0;

            $totalBobot = $mhs->indeksPrestasiSemester->sum('nilai_bobot');
            $totalSksAll = $mhs->nilai->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0);
            $ipk = $totalSksAll > 0 ? round($totalBobot / $totalSksAll, 2) : 0;

            $absensi = $mhs->absensi->first();
            $jml = $absensi ? $absensi->jml_sakit + $absensi->jml_izin + $absensi->jml_alfa : 0;

            $semesterSks = collect(range(1, 8))->mapWithKeys(fn($s) => [
                $s => $mhs->nilai->where('semester_ke', $s)->sum(fn($n) => $n->mataKuliah->jumlah_sks ?? 0)
            ]);

            $nilaiDetail = $nilaiSemester->map(fn($n) => [
                'kode_matkul' => $n->kode_matkul,
                'nama_matkul' => $n->mataKuliah->nama_matkul ?? '-',
                'kode_dosen' => $n->dosen->kode_dosen ?? '-',
                'nama_dosen' => $n->dosen->nama_dosen ?? '-',
                'jumlah_sks' => $n->mataKuliah->jumlah_sks ?? 0,
                'indeks_nilai' => $n->indeks_nilai ?? '-',
            ])->values();
            // $nilaiSemester = $mhs->nilai->where('semester_ke', $semester);
            // $nilaiDetail = $nilaiSemester->keyBy('kode_matkul');

            return [
                'no' => $index + 1,
                'nim' => $mhs->nim,
                'nama_mhs' => $mhs->nama_mhs,
                'nilai_per_matkul' => $nilaiDetail,
                'total_sks' => $totalSks,
                'jumlah_d' => $jumlah_d,
                'sks_d' => $totalSksD,
                'semester_sks' => $semesterSks->toArray(),
                'nilai_bobot' => $nilai_bobot,
                'ip_semester' => ['lalu' => $ipLalu, 'sekarang' => $ipSekarang],
                'ipk' => $ipk,
                'jml_sakit' => $absensi->jml_sakit ?? 0,
                'jml_izin' => $absensi->jml_izin ?? 0,
                'jml_alfa' => $absensi->jml_alfa ?? 0,
                'jml' => $jml,
                'nilai_penghayatan' => $absensi->nilai_penghayatan ?? '-',
                'status' => optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->status ?? 'N/A',
                'keterangan' => optional($mhs->indeksPrestasiSemester->firstWhere('semester', $semester))->keterangan ?? '-',
            ];
        });

        return view('buku-besar-view.tabel-buku-besar', compact('data', 'totalSemesters', 'tahun_akademik', 'semester', 'mataKuliahs', 'prodis', 'kelas', 'tahun', 'program_studi'));

        // dd($data);
    }
}
