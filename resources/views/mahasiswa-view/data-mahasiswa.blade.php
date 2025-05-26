@extends('layouts.app')

@section('content')
    <style>
        @import "bootstrap/dist/css/bootstrap.min.css";
        @import "@fortawesome/fontawesome-free/css/all.min.css";

        body,
        input,
        select,
        button,
        table {
            font-family: "Poppins", sans-serif;
        }

        thead th {
            background-color: #f4f4f4e8 !important;
            color: #000000cd !important;
            font-weight: 700;
            text-align: center;
        }

        td[rowspan] {
            vertical-align: middle !important;
        }

        table {
            font-size: 14px;
        }

        .pagination .page-link {
            border: none;
            color: #000;
            background-color: transparent;
            transition: background-color 0.3s ease;
            border-radius: 0;
        }

        .pagination .page-item {
            border: none;
        }

        .pagination .page-item.active .page-link {
            background-color: #ffa500; 
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
            border: none;
        }

        .pagination .page-link:hover {
            background-color: #ffd59a;
            color: #000;
            border-radius: 8px;
        }

        .sidebar-nav .nav-link {
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 158, 0, 0.5); 
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
        }

        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }

        /* Styling untuk search box dengan ikon */
        .custom-search-container {
            position: relative;
            width: 200px;
        }

        .custom-search {
            border-radius: 20px;
            border: 1px solid #ced4da;
            padding: 8px 15px 8px 35px; /* Padding kiri lebih besar untuk memberi ruang pada ikon */
            color: #333;
            width: 100%;
            font-family: "Poppins", sans-serif;
        }

        .custom-search::placeholder {
            color: #6c757d;
        }

        .custom-search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
        }

        /* Styling untuk dropdown angkatan dengan ikon */
        .custom-dropdown-container {
            position: relative;
            width: 180px;
        }

        .custom-dropdown {
            width: 100%;
            padding: 10px 12px 10px 35px; /* Padding kiri lebih besar untuk memberi ruang pada ikon */
            border: 1px solid #ced4da;
            border-radius: 20px;
            background-color: #fff;
            color: #333;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: border 0.3s ease, box-shadow 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20width='16'%20height='16'%20fill='gray'%20class='bi%20bi-caret-down-fill'%20viewBox='0%200%2016%2016'%3E%3Cpath%20d='M7.247%2011.14l-4.796-5.481c-.566-.647-.106-1.659.753-1.659h9.592c.86%200%201.32%201.012.753%201.659l-4.796%205.48a1%201%200%200%201-1.506%200z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px 16px;
            font-family: "Poppins", sans-serif;
        }

        .custom-dropdown-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
        }

        .custom-dropdown:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
            outline: none;
        }

        .custom-dropdown:hover {
            border-color: #7c3aed;
        }

        .custom-filter-button {
            border-radius: 20px;
            border: 1px solid #ced4da;
            padding: 8px 15px;
            color: #333;
            background-color: #fff;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-filter-button:hover {
            border-color: #7c3aed;
        }

        .custom-filter-button:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
            outline: none;
        }

        .custom-table th,
        .custom-table td {
            text-align: center;
            vertical-align: middle;
            padding: 10px;
        }

        .custom-header {
            font-weight: bold;
            color: #333;
        }

        .custom-pagination-button {
            border-radius: 50%;
            border: 1px solid #ced4da;
            padding: 8px;
            color: #333;
            background-color: #fff;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-pagination-button:hover {
            border-color: #7c3aed;
        }

        .custom-pagination-button:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
            outline: none;
        }

        .custom-detail-icon {
            color: #333;
            transition: color 0.3s ease;
            text-decoration: none;
        }

        .custom-detail-icon:hover {
            color: #7c3aed;
        }
    </style>

    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h2 class="mb-0 custom-header">Data Mahasiswa</h2>

            <div class="d-flex gap-3 flex-wrap">
                <!-- Search Box -->
                <form class="d-flex custom-search-container" role="search">
                    <i class="fas fa-magnifying-glass custom-search-icon"></i>
                    <input
                        type="search"
                        class="form-control custom-search"
                        placeholder="Search"
                        aria-label="Search"
                    />
                </form>

                <!-- Filter Angkatan -->
                <form class="custom-dropdown-container">
                    <i class="fas fa-users custom-dropdown-icon"></i>
                    <select class="custom-dropdown">
                        <option value="" selected>Angkatan</option>
                        <option value="20">Angkatan 20</option>
                        <option value="21">Angkatan 21</option>
                        <option value="22">Angkatan 22</option>
                        <option value="23">Angkatan 23</option>
                        <option value="24">Angkatan 24</option>
                        <option value="25">Angkatan 25</option>
                    </select>
                </form>

                <!-- Filter Button -->
                <button class="custom-filter-button">
                    <i class="fas fa-filter"></i> Filters
                </button>
            </div>
        </div>

        <!-- Table for Data Mahasiswa -->
        <div class="table-container">
            <table class="table table-bordered custom-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Kota Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Status<br>Mahasiswa</th>
                        <!-- <th>Alamat Mahasiswa</th>
                        <th>Nama Kabupaten</th>
                        <th>Kode Pos</th> -->
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Data -->
                    <tr>
                        <td>1</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- Repeated for pagination simulation -->
                    <tr>
                        <td>2</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>231511070</td>
                        <td>Aulia Putri Ramadhani</td>
                        <td>Kab. Garut</td>
                        <td>6 November 2006</td>
                        <td>Aktif</td>
                        <!-- <td>Jalan Ciwuruga No. 20</td>
                        <td>Kab. Bandung Barat</td>
                        <td>44151</td> -->
                        <td>081234567890</td>
                        <td>aulia@gmail.com</td>
                        <td>
                        </a>
                                <class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center align-items-center gap-3">
            <button class="custom-pagination-button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item disabled"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><span class="page-link">...</span></li>
                    <li class="page-item"><a class="page-link" href="#">8</a></li>
                    <li class="page-item"><a class="page-link" href="#">9</a></li>
                    <li class="page-item"><a class="page-link" href="#">10</a></li>
                </ul>
            </nav>
            <button class="custom-pagination-button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
@endsection