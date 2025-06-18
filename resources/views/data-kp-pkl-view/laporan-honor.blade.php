@extends('layouts.app')Add commentMore actions

@section('content')
    <div class="container mt-5">
        <div class="container mt-5">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <h2 style="font-weight:bold; margin:auto; text-align:center;">REKAPITULASI BIMBINGAN DAN PENGUJI SIDANG TUGAS AKHIR<br>
                     PROGRAM STUDI TEKNIK INFORMATIKA D-III (ANGKATAN 2021) <br>
                     DEPARTEMEN TEKNIK KOMPUTER DAN INFORMATIKA </h2>
                </div>
            </div>
        </div>
        

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead style="text-align: center; vertical-align: middle;">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">NIP</th>
                        <th rowspan="2">Nama Dosen</th>
                        <th colspan="2">PEMBIMBING</th>
                        <th rowspan="2">JML</th>
                        <th colspan="2">PENGUJI</th>
                        <th rowspan="2">JML</th>
                    </tr>
                    <tr>
                        <th>I</th>
                        <th>II</th>
                        <th>I</th>
                        <th>II</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>198501012010121001</td>
                        <td>Dr. Budi Santoso, M.Kom.</td>
                        <td style="text-align: center;">3</td> <td style="text-align: center;">0</td> <td style="text-align: center;"><strong>3</strong></td> <td style="text-align: center;">0</td> <td style="text-align: center;">0</td> <td style="text-align: center;"><strong>0</strong></td> </tr>
                    <tr>
                        <td>2</td>
                        <td>199002022015042002</td>
                        <td>Siti Aminah, S.T., M.T.</td>
                        <td style="text-align: center;">0</td>
                        <td style="text-align: center;">5</td>
                        <td style="text-align: center;"><strong>5</strong></td>
                        <td style="text-align: center;">4</td>
                        <td style="text-align: center;">0</td>
                        <td style="text-align: center;"><strong>4</strong></td>
                    </tr>
                    </tbody>
            </table>
        </div>

        <!-- Pagination Palsu -->
        <div class="d-flex justify-content-center">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><span class="page-link">«</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">»</span></li>
                </ul>
            </nav>
        </div>
    </div>
    @endsection