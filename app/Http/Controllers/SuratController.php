<?php

namespace App\Http\Controllers;

use App\Enums\StatusSurat;
use App\Enums\TahapVerifikasi;
use App\Models\Dosen;
use App\Models\Surat;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratController extends Controller
{
    public function viewDashboardPengaju(Request $request)
    {
        $user = auth()->user();
        $status = $request->query('status', 'all');

        $query = Surat::where('nim', $user->nim);
        if ($status !== 'all') {
            $query->where('status_surat', $status);
        }
        $surats = $query->orderBy('created_at', 'desc')->paginate(5);

        $suratCount = Surat::where('nim', $user->nim)->count();

        return view('surat-view.mahasiswa.dashboard-pengaju', compact('surats', 'suratCount', 'status'));
    }

    public function createPengajuan(Request $request)
    {
        $jenisSurat = $request->query('jenis_surat');

        if (is_null($jenisSurat)) {
            return redirect()->route('dashboard-pengaju')->with('error', 'Harap pilih jenis surat terlebih dahulu.');
        }

        $user = auth()->user();
        $data = null;

        if ($user->role === 'mahasiswa') {
            $data = Mahasiswa::where('nim', $user->nim)->first();
        } else {
            return redirect()->route('dashboard')->with('error', 'Role tidak dikenali.');
        }

        if (!$data) {
            return redirect()->route('dashboard')->with('error', 'Data pengguna tidak ditemukan.');
        }

        return view('surat-view.mahasiswa.form-pengajuan-pengaju', compact('data', 'jenisSurat'));
    }

    public function storePengajuan(Request $request)
    {
        $jenisSurat = $request->input('jenisSurat');
        $validator = Validator::make($request->all(), [
            'ipk' => 'required|numeric|between:0,4.00',
            'ditujukan' => 'required_if:jenisSurat,suratBeasiswa|string|max:255',
            'keperluan' => 'required|string',
            'berkas' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'ipk.required' => 'Kolom IPK wajib diisi.',
            'ditujukan.required_if' => 'Kolom ditujukan wajib diisi untuk jenis surat beasiswa.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $totalSurat = Surat::count() + 1;
        $kodeSurat = 'Sr-' . str_pad($totalSurat, 2, '0', STR_PAD_LEFT);

        $nama = $request->nama;
        $nim = $request->nim;
        $ipk = $request->ipk;
        $kelas = $request->kelas;
        $prodi = $request->prodi;
        $semester = $request->semester;
        $smt = $request->smt;
        $tahun = $request->tahun;
        $ditujukan = $request->ditujukan;
        $keperluan = $request->keperluan;

        $pdfData = compact('nama', 'nim', 'ipk', 'kelas', 'prodi', 'semester', 'smt', 'tahun', 'ditujukan', 'keperluan');

        $jsonPath = "temp/pdfData-{$kodeSurat}.json";
        Storage::put($jsonPath, json_encode($pdfData));

        $targetDir = public_path('laraview/' . $request->nim . '/' . $kodeSurat);
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $berkasPath = null;
        if ($request->hasFile('berkas')) {
            $file = $request->file('berkas');
            $originalName = $kodeSurat . '-berkas-pendukung.pdf';
            $file->move($targetDir, $originalName);
            $berkasPath = "laraview/{$nim}/{$kodeSurat}/{$originalName}";
        }

        Surat::create([
            'nim' => $request->nim,
            'kode_surat' => $kodeSurat,
            'ditujukan' => $jenisSurat === 'suratBeasiswa' ? $request->input('ditujukan') : '-',
            'keperluan' => $request->input('keperluan'),
            'berkas' => $berkasPath,
            'jenis_surat' => $jenisSurat,
        ]);

        if ($jenisSurat === 'suratBeasiswa') {
            $pdf = PDF::loadView('surat-view.template-surat-beasiswa', compact('pdfData', 'kodeSurat'));
        } else {
            $pdf = PDF::loadView('surat-view.template-surat-ormawa', compact('pdfData', 'kodeSurat'));
        }
        $pdfPath = $targetDir . '/' . $kodeSurat . '-preview-surat.pdf';
        $pdf->save($pdfPath);

        return redirect()->route('daftar-pengajuan-surat')->with([
            'success' => 'Pengajuan surat berhasil dibuat!',
            'kodeSurat' => $kodeSurat
        ]);
    }

    public function updatePengajuan(Request $request, $kodeSurat)
    {
        $surat = Surat::where('kode_surat', $kodeSurat)->firstOrFail();

        $jenisSurat = $surat->jenis_surat;

        $validator = Validator::make($request->all(), [
            'ditujukan' => 'nullable:jenisSurat,suratBeasiswa|string|max:255',
            'keperluan' => 'nullable|string',
        ], [
            'ditujukan.nullable' => 'Kolom ditujukan wajib diisi untuk jenis surat beasiswa.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nim = $request->nim;
        $nama = $request->nama;
        $ipk = $request->ipk;
        $kelas = $request->kelas;
        $prodi = $request->prodi;
        $semester = $request->semester;
        $smt = $request->smt;
        $tahun = $request->tahun;
        $ditujukan = $request->ditujukan;
        $keperluan = $request->keperluan;

        $pdfData = compact('nama', 'nim', 'ipk', 'kelas', 'prodi', 'semester', 'smt', 'tahun', 'ditujukan', 'keperluan');

        $jsonPath = "temp/pdfData-{$kodeSurat}.json";
        Storage::put($jsonPath, json_encode($pdfData));

        $targetDir = public_path('laraview/' . $nim . '/' . $kodeSurat);
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $surat->update([
            'ditujukan' => $jenisSurat === 'suratBeasiswa' ? $request->input('ditujukan') : '-',
            'keperluan' => $keperluan,
        ]);

        $pdfPath = $targetDir . '/' . $kodeSurat . '-preview-surat.pdf';
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        if ($jenisSurat === 'suratBeasiswa') {
            $pdf = PDF::loadView('surat-view.template-surat-beasiswa', compact('pdfData', 'kodeSurat'));
        } else {
            $pdf = PDF::loadView('surat-view.template-surat-ormawa', compact('pdfData', 'kodeSurat'));
        }
        $pdf->save($pdfPath);
        return redirect()->route('detail-pengajuan-surat', $surat->kode_surat)->with('success', 'Data surat berhasil diperbarui!');
    }


    public function updateDetailPengajuanSurat(Request $request, $kodeSurat)
    {
        // $jenisSurat = $request->input('jenisSurat');
        $surat = Surat::where('kode_surat', $kodeSurat)->firstOrFail();
        $jenisSurat = $surat->jenis_surat;
        $user = auth()->user();

        if ($user->role === 'tata_usaha') {
            $surat->update([
                'status_surat' => StatusSurat::DIPROSES->value,
                'tahap_verifikasi' => $request->tahap_verifikasi,
            ]);
        } elseif ($user->role === 'dosen') {
            $jsonPath = "temp/pdfData-{$kodeSurat}.json";
            $pdfData = null;

            if (Storage::exists($jsonPath)) {
                $pdfData = json_decode(Storage::get($jsonPath), true);
            }

            $targetDir = public_path('laraview/' . $surat->nim . '/' . $kodeSurat);
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $pdfPath = $targetDir . '/' . $kodeSurat . '-preview-surat.pdf';
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            $imagePath = public_path('images/ttd_digital.png');
            $src = file_exists($imagePath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath)) : '';
            if ($jenisSurat === 'suratBeasiswa') {
                $pdf = PDF::loadView('surat-view.template-surat-beasiswa', compact('src', 'pdfData', 'kodeSurat'));
            } else {
                $pdf = PDF::loadView('surat-view.template-surat-ormawa', compact('src', 'pdfData', 'kodeSurat'));
            }
            $pdf->save($pdfPath);
            $surat->update([
                'status_surat' => StatusSurat::DISETUJUI->value,
                'tahap_verifikasi' => TahapVerifikasi::TU->value,
            ]);
        } else {
            abort(403, 'Akses ditolak');
        }

        return redirect()->route('daftar-verifikasi-surat')->with('success', 'Pengajuan surat berhasil diperbarui!');
    }


    public function indexDaftarSurat(Request $request)
    {
        $user = auth()->user();
        $status = $request->query('status', 'all');

        $query = Surat::where('nim', $user->nim);

        if ($status !== 'all') {
            $query->where('status_surat', $status);
        }

        $surats = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('surat-view.mahasiswa.daftar-pengajuan-surat', compact('surats', 'status'));
    }

    public function viewDashboardReviewer1()
    {
        return view('surat-view.TU.dashboard-reviewer1');
    }
    public function indexDaftarSuratVerifikasi(Request $request)
    {
        $user = auth()->user();
        $status = $request->query('status', 'all');

        if ($user->role === 'tata_usaha') {
            $query = Surat::query();
            if ($status !== 'all') {
                $query->where('status_surat', $status);
            }
            $surats = $query->orderBy('updated_at', 'desc')->paginate(10);
        } elseif ($user->role === 'dosen') {
            // Ambil jabatan dosen dari user yang login
            $dosen = Dosen::where('kode_dosen', $user->kode_dosen)->first();
            $query = Surat::whereRaw('0 = 1'); // Query kosong untuk default tabel kosong

            if ($dosen && in_array($dosen->jabatan_dosen, ['Kaprodi', 'Kajur', 'wali dosen'])) {
                // Filter surat berdasarkan jabatan dosen
                $query = Surat::where('tahap_verifikasi', $dosen->jabatan_dosen)
                    ->where('status_surat', 'diproses');
                if ($status !== 'all') {
                    $query->where('status_surat', $status);
                }
            }

            $surats = $query->orderBy('updated_at', 'desc')->paginate(10);
        } else {
            abort(403, 'Akses ditolak');
        }

        return view('surat-view.TU.daftar-verifikasi-surat', compact('surats', 'status'));
    }

    public function indexDetailPengajuanSurat($kode_surat)
    {
        $user = auth()->user();

        $surat = Surat::where('kode_surat', $kode_surat)->first();
        $data = Mahasiswa::where('nim', $surat->nim)->first();

        $jsonPath = "temp/pdfData-{$kode_surat}.json";
        $pdfData = null;

        if (Storage::exists($jsonPath)) {
            $pdfData = json_decode(Storage::get($jsonPath), true);
        }

        return view('surat-view.TU.detail-pengajuan-surat', compact('user', 'surat', 'data', 'pdfData'));
    }
    
    public function createSurat($kode_surat)
    {
        $user = auth()->user();

        $surat = Surat::where('kode_surat', $kode_surat)->first();
        
        $targetDir = public_path('laraview/' . $surat->nim . '/' . $kode_surat);
        $pdfPath = $targetDir . '/' . $kode_surat . '-preview-surat.pdf';
        // $fileName = $surat->nim . '/' . $kode_surat . '/' . $kode_surat . '-preview-surat.pdf';

        // $pdf = Pdf::loadview('Pages.Mahasiswa.surat_perjanjian', compact('pengajuans'));
        // return view('suratPerjanjian'); // Tampilkan PDF di browser
        // return $pdf->stream();
        return response()->download($pdfPath);    
    }
}
