<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TugasAkhirController extends Controller
{
    public function pdptForm()
    {
        $angkatans = Kelas::distinct()->pluck('angkatan', 'angkatan');
        $prodis = Prodi::pluck('nama_prodi', 'kode_prodi');

        return view('tugas-akhir-view.generate-pdpt-ta', compact('angkatans', 'prodis'));
    }

    public function handleDownload(Request $request)
    {
        $request->validate([
            'program_studi' => 'required|exists:prodi,kode_prodi',
            'angkatan' => 'required|string',
            'jenis_laporan' => 'required|in:pdpt',
        ]);

        if ($request->jenis_laporan === 'pdpt') {
            return $this->generatePDPT($request);
        }

        abort(404);
    }

    public function generatePDPT(Request $request)
    {
        $request->validate([
            'program_studi' => 'required|exists:prodi,kode_prodi',
            'angkatan' => 'required|string',
        ]);

        try {
            $prodi = Prodi::where('kode_prodi', $request->program_studi)->firstOrFail();
            $namaProdi = $prodi->nama_prodi;

            $kaprodi = Dosen::where('jabatan_dosen', 'Kaprodi')->first();

            $data = TugasAkhir::with([
                'mahasiswa.kelas',
                'membimbing.dosen',
                'menguji.dosen',
            ])
                ->whereHas('mahasiswa.kelas', function ($query) use ($request, $prodi) {
                    $query->where('kode_prodi', $prodi->kode_prodi)
                        ->where('angkatan', $request->angkatan);
                })
                ->get()
                ->map(function ($item) {
                    $pembimbing1 = $item->membimbing->where('pembimbing_ke', 1)->first();
                    $pembimbing2 = $item->membimbing->where('pembimbing_ke', 2)->first();
                    $penguji1 = $item->menguji->where('penguji_ke', 1)->first();
                    $penguji2 = $item->menguji->where('penguji_ke', 2)->first();

                    return [
                        'nim' => $item->mahasiswa->first()->nim ?? '-',
                        'nama_mhs' => $item->mahasiswa->first()->nama_mhs ?? '-',
                        'kota' => $item->kota,
                        'pembimbing_1' => $pembimbing1 ? $pembimbing1->dosen->nama_dosen : '-',
                        'nidn_pembimbing_1' => $pembimbing1 ? $pembimbing1->dosen->nidn : '-',
                        'pembimbing_2' => $pembimbing2 ? $pembimbing2->dosen->nama_dosen : '-',
                        'nidn_pembimbing_2' => $pembimbing2 ? $pembimbing2->dosen->nidn : '-',
                        'penguji_1' => $penguji1 ? $penguji1->dosen->nama_dosen : '-',
                        'nidn_penguji_1' => $penguji1 ? $penguji1->dosen->nidn : '-',
                        'penguji_2' => $penguji2 ? $penguji2->dosen->nama_dosen : '-',
                        'nidn_penguji_2' => $penguji2 ? $penguji2->dosen->nidn : '-',
                    ];
                });

            if ($data->isEmpty()) {
                return redirect()->route('generate.pdpt.ta.form')->with('error', 'Tidak ada data untuk program studi dan angkatan yang dipilih.');
            }

            $currentDate = now()->format('d-m-Y');
            $filename = "laporan_pdpt_tugas_akhir_{$namaProdi}_{$request->angkatan}_{$currentDate}.pdf";

            $pdf = Pdf::loadView('tugas-akhir-view.laporan-pdpt-ta', compact('data', 'kaprodi', 'prodi'));

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return redirect()->route('generate.pdpt.ta.form')->with('error', 'Gagal menghasilkan laporan: ' . $e->getMessage());
        }
    }
}
