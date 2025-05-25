<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Surat;


class FormSuratController extends Controller
{
    public function create()
    {
        return view('surat-view.Form_Tes');
    }

public function store(Request $request)
    {
        // Validasi umum
        $rules = [
            'judul_surat' => 'required|string|max:255',
            'jenis_surat' => ['required', Rule::in(['Rekomendasi', 'Beasiswa', 'Pengantar', 'Lainnya'])],
            'isi_surat'   => 'required|string',
            'pesan'       => 'nullable|string',

            // Hanya salah satu dari nim atau kode_dosen
            'nim'         => 'nullable|required_without:kode_dosen',
            'kode_dosen'  => 'nullable|required_without:nim',
        ];

        // Validasi kondisional berdasarkan jenis_surat
        if ($request->jenis_surat === 'Rekomendasi') {
            $rules['tujuan_rekomendasi'] = 'required|string|max:255';
        }

        if ($request->jenis_surat === 'Beasiswa') {
            $rules['nama_perusahaan'] = 'required|string|max:255';
        }

        if ($request->jenis_surat === 'Pengantar') {
            $rules['program'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        // Handle validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buat kode surat unik
        $kodeSurat = strtoupper(Str::random(5));

        // Simpan data
        Surat::create([
            'kode_surat'         => $kodeSurat,
            'judul_surat'        => $request->judul_surat,
            'jenis_surat'        => $request->jenis_surat,
            'isi_surat'          => $request->isi_surat,
            'pesan'              => $request->pesan,
            'tgl_surat'          => now(),
            'status_surat'       => 'Draft', // default status

            'id_user'            => auth()->user()->id_user ?? null, // sesuaikan jika ada autentikasi

            'nim'                => $request->nim,
            'kode_dosen'         => $request->kode_dosen,
            'tujuan_rekomendasi' => $request->jenis_surat === 'Rekomendasi' ? $request->tujuan_rekomendasi : null,
            'nama_perusahaan'    => $request->jenis_surat === 'Beasiswa' ? $request->nama_perusahaan : null,
            'program'            => $request->jenis_surat === 'Pengantar' ? $request->program : null,
        ]);

        return redirect()->route('surat.index')->with('success', 'Surat berhasil disimpan!');
    }
}
