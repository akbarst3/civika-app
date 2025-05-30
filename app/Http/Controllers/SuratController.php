<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SuratController extends Controller
{
    public function viewDashboardPengaju()
    {
        return view('surat-view.mahasiswa.dashboard-pengaju');
    }

    public function createPengajuan()
    {
         // nama, nim, ipk, kelas, prodi, semester, smt, tahun, ditujukan, keperluan surat, berkas, kode_surat, id_user, jenis_surat, tgl_surat, status_surat
         $mahasiswa = Mahasiswa::with(['kelas', 'absensi'])
                    ->where('nim', 230101001)
                    ->first(); 
                    
                    // dd($mahasiswa);
         return view('surat-view.mahasiswa.form-pengajuan-pengaju', compact('mahasiswa'));

    }

    public function storePengajuan(Request $request)
    {
        // dd($request);
        // Validasi umum
        $validator = Validator::make($request->all(), [
            'ditujukan' => 'required|string|max:255',
            'keperluan' => 'required|string',
            'berkas' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $berkasPath = null;
        if ($request->hasFile('berkas')) {
            $berkasPath = $request->file('berkas')->store('berkas_pengajuan', 'public');
        }

        // nama, nim, ipk, kelas, prodi, semester, smt, tahun, ditujukan, keperluan surat, berkas, kode_surat, id_user, jenis_surat, tgl_surat, status_surat
        $totalSurat = Surat::count() + 1;
        $kodeSurat = 'Sr-' . str_pad($totalSurat, 2, '0', STR_PAD_LEFT);
        Surat::create([
            'nim' => $request->nim,
            'kode_surat' => $kodeSurat, 
            'ditujukan' => $request->input('ditujukan'),
            'keperluan' => $request->input('keperluan'),
            'berkas' => $berkasPath,
            'jenis_surat'=> "Beasiswa",
        ]);

        return redirect()->route('daftar-pengajuan-surat')->with('success', 'Pengajuan surat berhasil dibuat!');
    }


    public function updateDetailPengajuanSurat(Request $request, $id)
    {
        // dd($request);
        // Validasi input
        $validator = Validator::make($request->all(), [
            'ditujukan' => 'required|string|max:255',
            'keperluan' => 'required|string',
            'berkas' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'tahap_verifikasi' => 'required|in:Kaprodi,Kajur,WaliDosen',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil data surat lama
        $surat = Surat::findOrFail($id);

        // Simpan file baru jika ada, kalau tidak gunakan file lama
        $berkasPath = $surat->berkas;
        if ($request->hasFile('berkas')) {
            // Hapus file lama jika ada
            if ($berkasPath && Storage::disk('public')->exists($berkasPath)) {
                Storage::disk('public')->delete($berkasPath);
            }

            // Simpan file baru
            $berkasPath = $request->file('berkas')->store('berkas_pengajuan', 'public');
        }

        // Update field hanya jika ada perubahan
        $surat->update([
            'nim' => $request->nim ?? $surat->nim,
            'ditujukan' => $request->input('ditujukan', $surat->ditujukan),
            'keperluan' => $request->input('keperluan', $surat->keperluan),
            'berkas' => $berkasPath,
            'tahap_verifikasi' => $request->tahap_verifikasi,
        ]);

        return redirect()->route('daftar-verifikasi-surat')->with('success', 'Pengajuan surat berhasil diperbarui!');
    }


    public function indexDaftarSurat()
    {
         $surats = Surat::where('nim', 230101001)->get();                    
                    // dd($surats);
        return view('surat-view.mahasiswa.daftar-pengajuan-surat', compact('surats'));
    }

    public function viewDashboardReviewer1()
    {
        return view('surat-view.TU.dashboard-reviewer1');
    }

    public function indexDaftarSuratDisetujui()
    {
        //  $surats = Surat::where('nim', 230101001)->get();                    
        //             // dd($surats);
        // return view('surat-view.mahasiswa.daftar-pengajuan-surat', compact('surats'));
    }

    public function indexDaftarSuratVerifikasi()
    {
        $surats = Surat::all();
        return view('surat-view.TU.daftar-verifikasi-surat', compact('surats'));
    }
   
    public function indexDetailPengajuanSurat($kode_surat)
    {
        // nama, nim, ipk, kelas, prodi, semester, smt, tahun, ditujukan, keperluan surat, berkas, kode_surat, id_user, jenis_surat, tgl_surat, status_surat
        $mahasiswa = Mahasiswa::with(['kelas', 'absensi'])
                ->where('nim', 230101001)
                ->first(); 
        $surat = Surat::where('kode_surat', $kode_surat)->first();
                
                // dd($mahasiswa);
        return view('surat-view.TU.detail-pengajuan-surat', compact('mahasiswa', 'surat'));

    }

    public function viewDashboardReviewer2()
    {
        return view('surat-view.TU.dashboard-reviewer1');
    }

    public function indexDaftarSuratDisetujui2()
    {
         $surats = Surat::where('nim', 230101001)->get();                    
                    // dd($surats);
        return view('surat-view.mahasiswa.daftar-pengajuan-surat', compact('surats'));
    }

    public function indexDaftarSuratVerifikasi2()
    {
        $surats = Surat::where('tahap_verifikasi', "kaprodi")->get();    
        return view('surat-view.TU.daftar-verifikasi-surat', compact('surats'));
    }

    // // Display form for pengajuan rekomendasi
    // public function dashboardSuratRokumendasi()
    // {
    //     return view('surat-view.form-pengajuan-pengaju_rokumendasi');
    // }

    // // Display form for pengajuan beasiswa
    // public function dashboardSuratBeasiswa()
    // {
    //     return view('surat-view.form-pengajuan-pengaju_beasiswa');
    // }

}
