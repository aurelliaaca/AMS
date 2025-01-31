@extends('layouts.sidebar')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        
        /* Reset & Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        body {
            min-height: 100vh;
        }

        /* Container Styles */
        .container {
            box-shadow: 2px 2px 10px #9497f5;
            margin: 5px auto;
            padding: 20px 10px;
            background-color: #fff;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            transition: 0.3s linear all;
        }

        .container:hover {
            box-shadow: 4px 4px 20px #DADADA;
        }

        /* Header Styles */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-title {
            text-align: left;
            margin: 0;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #4f52ba;
            border-radius: 8px;
            padding: 8px 12px;
            width: 300px;
        }

        .search-bar input {
            border: none;
            background: none;
            outline: none;
            font-size: 14px;
            width: 100%;
            color: #fff;
        }

        .search-bar input::placeholder {
            color: #fff;
        }

        .search-bar svg {
            margin-right: 8px;
            color: #fff;
        }

        .filter-select {
            border: none;
            border-radius: 8px;
            background-color: #4f52ba; 
            padding: 8px 12px;
            font-size: 14px;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
        }

        /* Table Styles */
        .table {
            width: 100%;
            font-size: 18px;
            border-collapse: collapse;
            table-layout: auto;
            padding-left: 30px;
        }

        .table thead th {
            font-weight: bold;
            text-align: left;
            padding: 15px;
            color: #fff;
            background-color: #4f52ba;
            font-size: 16px;
        }

        .table thead th:nth-child(12),
        .table tbody td:nth-child(12) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(8) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table tbody td:nth-child(8) {
            padding-left: 60px;
        }

        .table tbody th:nth-child(13){
            padding-left: 70px;
        }

        .table thead th:nth-child(3) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(5) {
            min-width: 250px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(6) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(7) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(8) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(9) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(10) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(11) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(13) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(14) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(15) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(16) {
            min-width: 200px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(4),
        .table tbody td:nth-child(4) {
            min-width: 300px;
            max-width: 500px;
            text-align: middle;
            word-wrap: break-word;
        }

        .table tbody tr {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            padding: 15px;
        }

        .table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .table tbody td {
            padding: 15px;
            font-size: 16px;
            color: #333;
            vertical-align: middle;
            padding-left: 30px;
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .table th {
            text-align: left;
        }

        .table th:nth-child(1), .table td:nth-child(1) {
            width: 150px;
        }

        /* Tambahkan padding ke semua td */
        .table td {
            padding-left: 10px; /* Atur padding kiri untuk menggeser konten ke kanan */
        }

        /* Styling untuk pagination */
        .pagination {
            justify-content: center; /* Pusatkan pagination */
            margin-top: 20px; /* Jarak atas untuk pagination */
        }

        .pagination .page-item.active .page-link {
            background-color: #4f52ba; /* Warna latar belakang untuk halaman aktif */
            border-color: #4f52ba; /* Warna border untuk halaman aktif */
            color: white; /* Warna teks untuk halaman aktif */
        }

        .pagination .page-link {
            color: #4f52ba; /* Warna teks untuk link */
            border: 1px solid #4f52ba; /* Border untuk link */
        }

        .pagination .page-link:hover {
            background-color: #b0b2eb; /* Warna latar belakang saat hover */
            color: white; /* Warna teks saat hover */
        }

        .pagination .page-item.disabled .page-link {
            color: #4f52ba; /* Warna teks untuk link yang dinonaktifkan */
            background-color: transparent; /* Hapus latar belakang saat dinonaktifkan */
            border-color: #4f52ba; /* Warna border untuk link yang dinonaktifkan */
        }
    </style>

    <div class="main">
        <div class="container">
            <div class="header-container">
                <h2 class="header-title"><strong>Data Jaringan</strong></h2>
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                        <path fill="currentColor" d="M10 18a8 8 0 1 1 0-16a8 8 0 0 1 0 16Zm7.707-2.707a1 1 0 0 0 0 1.414l3 3a1 1 0 0 0 1.414-1.414l-3-3a1 1 0 0 0-1.414 0Z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTable()" />
                </div>
            </div>
            <div>
            <div class="table-responsive">
                <table class="table" id="jaringanTable" style="min-width: 1000px; table-layout: auto;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="roFilter" onchange="filterTable()">
                                    <option value="">RO</option>
                                    <option value="Batam">Batam</option>
                                    <option value="Bekasi">Bekasi</option>
                                    <option value="Cilegon">Cilegon</option>
                                    <option value="Jabatim">Jabatim</option>
                                    <option value="Jakarta">Jakarta</option>
                                    <option value="Jambi">Jambi</option>
                                    <option value="Lampung">Lampung</option>
                                    <option value="Palembang">Palembang</option>
                                    <option value="Sumbagut">Sumbagut</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="tipeJaringanFilter" onchange="filterTable()">
                                    <option value="">Tipe Jaringan</option>
                                    <option value="Backbone">Backbone</option>
                                    <option value="ROW PGN">ROW PGN</option>
                                    <option value="Lastmile">Lastmile</option>
                                </select>
                            </th>
                            <th style="width: 10%;">Segmen</th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="jartatupFilter" onchange="filterTable()">
                                    <option value="">Jartatup Jartaplok</option>
                                    <option value="Jartatup">Jartatup</option>
                                    <option value="Jartaplok">Jartaplok</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="mainlinkFilter" onchange="filterTable()">
                                    <option value="">Mainlink Backuplink</option>
                                    <option value="Jartatup">Single</option>
                                    <option value="Jartaplok">Protected</option>
                                </select>
                            </th>
                            <th style="width: 10%;">Panjang</th>
                            <th style="width: 10%;">Panjang Drawing</th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="jumlahCoreFilter" onchange="filterTable()">
                                    <option value="">Jumlah Core</option>
                                    <option value="12">12</option>
                                    <option value="24">24</option>
                                    <option value="48">48</option>
                                    <option value="96">96</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="jenisKabelFilter" onchange="filterTable()">
                                    <option value="">Jenis Kabel</option>
                                    <option value="Kabel Tanah">Kabel Tanah</option>
                                    <option value="Kabel Uadara">Kabel Uadara</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="tipeKabelFilter" onchange="filterTable()">
                                    <option value="">Tipe Kabel</option>
                                    <option value="G652D">G652D</option>
                                    <option value="G655C">G655C</option>
                                    <option value="G654C">G654C</option>
                                    <option value="G654B">G654B</option>
                                </select>
                            </th>
                            <th style="width: 10%;">Kode Site Insan</th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="travellingFilter" onchange="filterTable()">
                                    <option value="">Travelling Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="2:00:00">2:00:00</option>
                                    <option value="3:00:00">3:00:00</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="verificationFilter" onchange="filterTable()">
                                    <option value="">Verification Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="2:00:00">2:00:00</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="restorationFilter" onchange="filterTable()">
                                    <option value="">Restoration Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="3:00:00">3:00:00</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="correctiveFilter" onchange="filterTable()">
                                    <option value="">Total Corrective Time</option>
                                    <option value="0:00:00">0:00:00</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="2:00:00">2:00:00</option>
                                    <option value="3:00:00">3:00:00</option>
                                    <option value="4:00:00">4:00:00</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jaringanData as $data)
                        <tr>
                            <td>{{ $data->id_jaringan }}</td>
                            <td>{{ $data->RO }}</td>
                            <td>{{ $data->tipe_jaringan }}</td>
                            <td>{{ $data->segmen }}</td>
                            <td>{{ $data->jartatup_jartaplok }}</td>
                            <td>{{ $data->mainlink_backuplink}}</td>
                            <td>{{ $data->panjang }}</td>
                            <td>{{ $data->panjang_drawing }}</td>
                            <td>{{ $data->jumlah_core }}</td>
                            <td>{{ $data->jenis_kabel }}</td>
                            <td>{{ $data->tipe_kabel }}</td>
                            <td>{{ $data->kode_site_insan }}</td>
                            <td>{{ $data->travelling_time }}</td>
                            <td>{{ $data->verification_time }}</td>
                            <td>{{ $data->restoration_time }}</td>
                            <td>{{ $data->total_corrective_time }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $jaringanData->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $jaringanData->previousPageUrl() }}" tabindex="-1">Previous</a>
                    </li>

                    <!-- Menampilkan hanya 3 halaman: halaman sebelumnya, halaman saat ini, dan halaman berikutnya -->
                    @if ($jaringanData->currentPage() > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $jaringanData->url($jaringanData->currentPage() - 1) }}">{{ $jaringanData->currentPage() - 1 }}</a>
                        </li>
                    @endif

                    <li class="page-item active">
                        <a class="page-link" href="#">{{ $jaringanData->currentPage() }}</a>
                    </li>

                    @if ($jaringanData->currentPage() < $jaringanData->lastPage())
                        <li class="page-item">
                            <a class="page-link" href="{{ $jaringanData->url($jaringanData->currentPage() + 1) }}">{{ $jaringanData->currentPage() + 1 }}</a>
                        </li>
                    @endif

                    <li class="page-item {{ $jaringanData->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $jaringanData->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script>
        let allData = []; // Array untuk menyimpan semua data

        // Fungsi untuk mengisi allData dengan data dari tabel
        function initializeData() {
            const table = document.getElementById('jaringanTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                const rowData = [];
                for (let j = 0; j < cells.length; j++) {
                    rowData.push(cells[j].innerText.toLowerCase());
                }
                allData.push(rowData);
            }
        }

        function filterTable() {
            const roFilter = document.getElementById('roFilter').value.toLowerCase();
            const tipeJaringanFilter = document.getElementById('tipeJaringanFilter').value.toLowerCase();
            const jartatupFilter = document.getElementById('jartatupFilter').value.toLowerCase();
            const mainlinkFilter = document.getElementById('mainlinkFilter').value.toLowerCase();
            const coreFilter = document.getElementById('jumlahCoreFilter').value.toLowerCase();
            const kabelFilter = document.getElementById('kabelFilter').value.toLowerCase();
            const tipeKabelFilter = document.getElementById('tipeKabelFilter').value.toLowerCase();
            const travellingFilter = document.getElementById('travellingFilter').value.toLowerCase();
            const verificationFilter = document.getElementById('verificationFilter').value.toLowerCase();
            const restorationFilter = document.getElementById('restorationFilter').value.toLowerCase();
            const correctiveFilter = document.getElementById('correctiveFilter').value.toLowerCase();
            const table = document.getElementById('jaringanTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                rows[i].style.display = 'none'; // Sembunyikan semua baris
            }

            // Filter data berdasarkan input
            for (let i = 0; i < allData.length; i++) {
                let display = true;

                // Cek setiap filter
                if (roFilter && allData[i][1] !== roFilter) {
                    display = false;
                }
                if (tipeJaringanFilter && allData[i][2] !== tipeJaringanFilter) {
                    display = false;
                }
                if (segmenFilter && allData[i][3].indexOf(segmenFilter) === -1) {
                    display = false;
                }
                if (jartatupFilter && allData[i][4].indexOf(jartatupFilter) === -1) {
                    display = false;
                }
                if (mainlinkFilter && allData[i][5].indexOf(mainlinkFilter) === -1) {
                    display = false;
                }
                if (jumlahCoreFilter && allData[i][6] !== jumlahCoreFilter) {
                    display = false;
                }
                if (jenisKabelFilter && allData[i][7] !== jenisKabelFilter) {
                    display = false;
                }
                if (tipeKabelFilter && allData[i][8] !== tipeKabelFilter) {
                    display = false;
                }
                if (travellingFilter && allData[i][9] !== travellingFilter) {
                    display = false;
                }
                if (verificationFilter && allData[i][10] !== verificationFilter) {
                    display = false;
                }
                if (restorationFilter && allData[i][11] !== restorationFilter) {
                    display = false;
                }
                if (correctiveFilter && allData[i][12] !== correctiveFilter) {
                    display = false;
                }

                // Tampilkan baris yang sesuai
                if (display) {
                    // Temukan baris yang sesuai di tabel
                    for (let k = 1; k < rows.length; k++) {
                        const cells = rows[k].getElementsByTagName('td');
                        if (allData[i].every((val, index) => val === cells[index].innerText.toLowerCase())) {
                            rows[k].style.display = ''; // Tampilkan baris yang sesuai
                        }
                    }
                }
            }
        }

        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('jaringanTable');
            const rows = table.getElementsByTagName('tr');

            // Hapus semua baris yang ada
            for (let i = 1; i < rows.length; i++) {
                rows[i].style.display = 'none'; // Sembunyikan semua baris
            }

            // Filter data berdasarkan input pencarian
            for (let i = 0; i < allData.length; i++) {
                let display = false;

                // Cek setiap kolom untuk mencocokkan input pencarian
                for (let j = 0; j < allData[i].length; j++) {
                    if (allData[i][j].indexOf(filter) > -1) {
                        display = true; // Jika ada yang cocok, tampilkan baris
                        break;
                    }
                }

                // Tampilkan baris yang sesuai
                if (display) {
                    // Temukan baris yang sesuai di tabel
                    for (let k = 1; k < rows.length; k++) {
                        const cells = rows[k].getElementsByTagName('td');
                        if (allData[i].every((val, index) => val === cells[index].innerText.toLowerCase())) {
                            rows[k].style.display = ''; // Tampilkan baris yang sesuai
                        }
                    }
                }
            }
        }

        // Panggil fungsi initializeData saat halaman dimuat
        window.onload = initializeData;
    </script>
@endsection