@extends('layouts.sidebar')

@section('content')
    <!-- Pastikan jQuery dan SweetAlert2 dimuat -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Kemudian impor Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Meta tag CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('path/to/your/style.css') }}">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Link ke Font Awesome -->
    <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>

    <!-- Link ke SweetAlert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
            font-size: 12px;
        }
        
        .container {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
.filter-container {
    display: flex;
    gap: 20px;
    align-items: center;
    width: 100%;
}

/* Memastikan setiap elemen dalam filter-container memiliki ukuran yang sama */
.filter-container > * {
    flex: 1;
    min-width: 150px; /* Menentukan lebar minimum agar tetap responsif */
}

/* Styling umum untuk dropdown dan search bar */
.filter-container select, 
.filter-container .search-bar input {
    width: 100%;
    font-size: 12px;
    padding: 12px; /* Padding seragam */
    border: 1px solid #4f52ba;
    border-radius: 5px;
    background-color: #fff;
    transition: border-color 0.3s;
    height: 42px; /* Menyamakan tinggi semua elemen */
}

/* Menghilangkan gaya default pada dropdown */
.filter-container select {
    appearance: none; /* Menghilangkan default styling bawaan browser */
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}

/* Styling khusus untuk search bar */
.filter-container .search-bar {
    flex: 1; /* Search bar memiliki ukuran yang sama dengan dropdown */
}

/* Menyesuaikan ukuran dropdown "Region" */
#roFilter {
    max-width: 250px; /* Atur lebar maksimum untuk dropdown Region */
}

.filter-container .search-bar input {
    outline: none;
}

/* Efek saat fokus */
.filter-container select:focus, 
.filter-container .search-bar input:focus {
    border-color: #4f52ba;
    box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
}

/* Styling untuk Select2 */
.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--multiple {
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 5px;
    background-color: #fff;
    transition: border-color 0.3s;
    border: 1px solid #4f52ba !important;
    height: 42px; /* Samakan tinggi Select2 dengan elemen lain */
    display: flex;
    align-items: center;
}

/* Fokus pada Select2 */
.select2-container--default .select2-selection--multiple:focus {
    border-color: #4f52ba !important;
    box-shadow: 0 0 5px rgba(79, 82, 186, 0.5) !important;
}

/* Placeholder Select2 */
.select2-container--default .select2-selection--multiple .select2-selection__placeholder {
    color: #4f52ba;
    font-size: 12px;
}

/* Warna border saat dropdown Select2 terbuka */
.select2-container--open .select2-selection {
    border-color: #4f52ba;
    box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
}

/* Styling hasil dropdown Select2 */
.select2-results__option {
    font-size: 12px;
    padding: 10px;
}

/* Warna opsi yang disorot di Select2 */
.select2-results__option--highlighted {
    background-color: #4f52ba !important;
    color: #fff;
}
    
    /* -------------------------- TABLE -------------------------- */
        .table-container {
            width: 100%;
            max-width: 100%;
            border-radius: 8px;
            position: relative;
        }
        
        table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            table-layout: auto;
        }
        
        th, td {
            padding: 12px !important;
            text-align: center !important;
            white-space: normal !important;
            border-bottom: 1px solid #ddd !important;
        }
        
        th {
            background-color: rgba(209, 210, 241, 0.316);
            color: #4f52ba;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        
        .no-data {
            text-align: center;
            color: rgba(79, 82, 186, 0.2);
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: rgba(209, 210, 241, 0.316);
        }

    /* -------------------------- MODAL -------------------------- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .form-container {
         display: flex;
        justify-content: space-between;
        gap: 10px; /* Perkecil jarak antar kolom */
        }

        .left-column,
        .right-column {
        width: 50%; /* Membuat kedua kolom seimbang */
        }


        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* Ubah menjadi 10% dari atas untuk menggeser modal lebih ke bawah */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Atur lebar modal menjadi 80% dari layar */
            max-width: 800px; /* Maksimal lebar 800px */
            min-height: 400px; /* Tambahkan tinggi minimum untuk modal */
            border-radius: 8px; /* Tambahkan border-radius untuk sudut yang lebih halus */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan untuk efek */
            font-weight: normal; /* Atur font-weight menjadi normal untuk semua teks di dalam modal */
        }

        .modal-close-btn {
            color: #aaa; /* Warna tombol */
            float: right; /* Mengapung ke kanan */
            font-size: 28px; /* Ukuran font */
            font-weight: bold; /* Pastikan font tidak diubah menjadi normal */
            border: none; /* Tanpa border */
            background: none; /* Tanpa latar belakang */
            cursor: pointer; /* Kursor pointer saat hover */
            margin: -10px -10px 20px 0; /* Tambahkan margin untuk penempatan yang baik */
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: normal; /* Pastikan label tidak bold */
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-weight: normal; /* Pastikan input tidak bold */
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.3);
            outline: none;
        }

    /* -------------------------- BUTTON -------------------------- */

        .add-button {
            background-color: #4f52ba;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: auto;
            margin-top: 10px;
            margin-right: 10px;
        }

        .add-button:hover {
            background-color: #3e4a9a;
        }

        .edit-btn {
            background-color: #4f52ba;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-close-btn:hover,
        .modal-close-btn:focus {
            color: black; /* Warna saat hover */
            text-decoration: none; /* Tanpa garis bawah */
            cursor: pointer; /* Kursor pointer saat hover */
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .export-button {
            background-color: #4f52ba; /* Warna latar belakang tombol */
            color: white; /* Warna teks tombol */
            border: none; /* Menghilangkan border default */
            padding: 10px; /* Padding tombol */
            border-radius: 5px; /* Sudut melengkung tombol */
            cursor: pointer; /* Pointer saat hover */
            font-size: 16px; /* Ukuran font tombol */
            transition: background-color 0.3s; /* Transisi warna latar belakang */
            width: 100%; /* Tombol mengisi lebar penuh */
        }

        .export-button:hover {
            background-color: #6f86e0; /* Warna latar belakang saat hover */
        }

        .modal-footer {
            display: flex; /* Menggunakan flexbox untuk mengatur posisi */
            justify-content: flex-end; /* Mengatur tombol ke kanan */
            margin-top: 20px; /* Jarak atas untuk pemisahan */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #4f52ba;
            margin: 0;
        }

        .swal2-cancel {
            background-color: #4f52ba !important;
            color: white !important;
            border: none !important;
            border-radius: 4px !important;
            padding: 10px 10px !important;
            font-size: 14px !important;
            cursor: pointer !important;
            margin-left: 10px;
        }

        .swal2-confirm {
            background-color: #dc3545 !important;
            color: white !important;
            border: none !important;
            border-radius: 4px !important;
            padding: 10px 10px !important;
            font-size: 14px !important;
            cursor: pointer !important;
            margin-right: 10px;
        }

        .swal2-confirm2 {
            background-color:  #4f52ba !important;
            color: white !important;
            border: none !important;
            border-radius: 4px !important;
            padding: 10px 10px !important;
            font-size: 14px !important;
            cursor: pointer !important;
            margin-right: 10px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

/* -------------------------- FILTER -------------------------- */
.filter-container {
    display: flex;
    gap: 20px;
    align-items: center;
    width: 100%;
}

/* Memastikan setiap elemen dalam filter-container memiliki ukuran yang sama */
.filter-container > * {
    flex: 1;
    min-width: 150px; /* Menentukan lebar minimum agar tetap responsif */
}

/* Styling umum untuk dropdown dan search bar */
.filter-container select, 
.filter-container .search-bar input {
    width: 100%;
    font-size: 12px;
    padding: 12px; /* Padding seragam */
    border: 1px solid #4f52ba;
    border-radius: 5px;
    background-color: #fff;
    transition: border-color 0.3s;
    height: 42px; /* Menyamakan tinggi semua elemen */
}

/* Menghilangkan gaya default pada dropdown */
.filter-container select {
    appearance: none; /* Menghilangkan default styling bawaan browser */
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}

/* Styling khusus untuk search bar */
.filter-container .search-bar {
    flex: 1; /* Search bar memiliki ukuran yang sama dengan dropdown */
}

/* Menyesuaikan ukuran dropdown "Region" */
#roFilter {
    max-width: 250px; /* Atur lebar maksimum untuk dropdown Region */
}

.filter-container .search-bar input {
    outline: none;
}

/* Efek saat fokus */
.filter-container select:focus, 
.filter-container .search-bar input:focus {
    border-color: #4f52ba;
    box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
}

/* Styling untuk Select2 */
.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--multiple {
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 5px;
    background-color: #fff;
    transition: border-color 0.3s;
    border: 1px solid #4f52ba !important;
    height: 42px; /* Samakan tinggi Select2 dengan elemen lain */
    display: flex;
    align-items: center;
}

/* Fokus pada Select2 */
.select2-container--default .select2-selection--multiple:focus {
    border-color: #4f52ba !important;
    box-shadow: 0 0 5px rgba(79, 82, 186, 0.5) !important;
}

/* Placeholder Select2 */
.select2-container--default .select2-selection--multiple .select2-selection__placeholder {
    color: #4f52ba;
    font-size: 12px;
}

/* Warna border saat dropdown Select2 terbuka */
.select2-container--open .select2-selection {
    border-color: #4f52ba;
    box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
}

/* Styling hasil dropdown Select2 */
.select2-results__option {
    font-size: 12px;
    padding: 10px;
}

/* Warna opsi yang disorot di Select2 */
.select2-results__option--highlighted {
    background-color: #4f52ba !important;
    color: #fff;
}

    td {
        font-weight: normal; /* Atur menjadi normal untuk semua td */
    }

.modal-overlay {
    display: none; /* Tersembunyi secara default */
    position: fixed; /* Tetap di tempat */
    z-index: 1000; /* Di atas elemen lain */
    left: 0;
    top: 0;
    width: 100%; /* Lebar penuh */
    height: 100%; /* Tinggi penuh */
    background-color: rgba(0, 0, 0, 0.5); /* Latar belakang hitam dengan transparansi */
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto; /* 5% dari atas dan tengah */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Atur lebar modal menjadi 80% dari layar */
    max-width: 800px; /* Maksimal lebar 800px */
    border-radius: 8px; /* Tambahkan border-radius untuk sudut yang lebih halus */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan untuk efek */
}

.modal-close-btn:hover,
.modal-close-btn:focus {
    color: black; /* Warna saat hover */
    text-decoration: none; /* Tanpa garis bawah */
    cursor: pointer; /* Kursor pointer saat hover */
}

.form-container {
    display: flex; /* Menggunakan flexbox untuk layout */
    justify-content: space-between; /* Spasi antara kolom */
    flex-wrap: wrap; /* Membungkus kolom jika diperlukan */
}

.left-column, .right-column {
    width: 48%; /* Lebar kolom */
}

.form-group {
    margin-bottom: 15px; /* Jarak antar grup */
    width: 100%; /* Lebar penuh untuk setiap grup */
}

.form-group label {
    font-weight: normal; /* Label tebal */
}

input[type="text"] {
    width: 100%; /* Lebar penuh untuk input */
    padding: 8px; /* Padding untuk input */
    border: 1px solid #ccc; /* Border untuk input */
    border-radius: 4px; /* Sudut melengkung untuk input */
}

.modal-content h2 {
    font-size: 24px; /* Ubah ukuran font menjadi lebih besar */
    margin-bottom: 20px; /* Tambahkan margin bawah untuk jarak */
    text-align: center; /* Memusatkan teks */
    font-weight: normal; /* Pastikan judul tidak bold */
}

.modal-content label {
    font-weight: normal; /* Pastikan label tidak bold */
}

.modal-content p {
    font-weight: normal; /* Pastikan paragraf tidak bold */
}

.modal-content input[type="text"] {
    font-weight: normal; /* Pastikan input tidak bold */
}

.button-container {
    display: flex;
}
.add-button {
    flex: 1; /* Membuat kedua tombol memiliki lebar yang sama */
    margin-right: 10px; /* Jarak antar tombol */
}
.add-button:last-child {
    margin-right: 0; /* Menghilangkan margin kanan pada tombol terakhir */
}

.edit-button {
    background-color: #4f52ba; /* Mengatur warna latar belakang tombol */
    color: white; /* Mengatur warna teks tombol */
    border: none; /* Menghilangkan border default */
    padding: 10px 20px; /* Mengatur padding tombol */
    border-radius: 5px; /* Mengatur sudut tombol */
    font-size: 13px; /* Mengatur ukuran font */
    font-weight: 600; /* Mengatur ketebalan font */
    cursor: pointer; /* Mengubah kursor saat hover */
    transition: background-color 0.3s ease; /* Efek transisi saat hover */
}

.edit-button:hover {
    background-color: #6064db; /* Warna saat hover */
}

.jaringan-modal-overlay {
    position: fixed; /* Mengatur posisi tetap */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Latar belakang semi-transparan */
    display: flex; /* Menggunakan flexbox untuk memusatkan konten */
    justify-content: center; /* Memusatkan secara horizontal */
    align-items: center; /* Memusatkan secara vertikal */
    z-index: 1000; /* Pastikan modal berada di atas elemen lain */
}

.jaringan-modal-content {
    background-color: white; /* Latar belakang konten modal */
    padding: 20px; /* Padding di dalam konten modal */
    border-radius: 5px; /* Sudut melengkung */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); /* Bayangan untuk efek kedalaman */
    width: 400px; /* Lebar konten modal */
    max-width: 90%; /* Maksimal lebar 90% dari viewport */
    position: relative; /* Pastikan konten modal memiliki posisi relatif */
}

.jaringan-modal-close-btn {
    cursor: pointer; /* Pointer saat hover */
    font-size: 20px; /* Ukuran font untuk tombol tutup */
    float: right; /* Mengatur posisi tombol tutup di kanan */
}

.jaringan-export-button {
    background-color: #4f52ba; /* Warna latar belakang tombol */
    color: white; /* Warna teks tombol */
    border: none; /* Menghilangkan border default */
    padding: 10px; /* Padding tombol */
    border-radius: 5px; /* Sudut melengkung tombol */
    cursor: pointer; /* Pointer saat hover */
    font-size: 16px; /* Ukuran font tombol */
    transition: background-color 0.3s; /* Transisi warna latar belakang */
    width: 100%; /* Tombol mengisi lebar penuh */
}

.jaringan-export-button:hover {
    background-color: #6f86e0; /* Warna latar belakang saat hover */
}

.jaringan-modal-footer {
    display: flex; /* Menggunakan flexbox untuk mengatur posisi */
    justify-content: flex-end; /* Mengatur tombol ke kanan */
    margin-top: 20px; /* Jarak atas untuk pemisahan */
}
    </style>

<div class="main">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Jaringan</h3>
            <div class="button-container">
                <button class="add-button" style="width: 150px;" onclick="importData()">Import</button>
                <button class="add-button" style="width: 150px;" onclick="storeJaringan()">Tambah Jaringan</button>
                <button class="add-button" style="width: 150px;" onclick="showExportModal()">Export</button>
            </div>
        </div>
        
        <div class="filter-container">
            <div>
                <select id="roFilter" name="region[]" multiple data-placeholder="Pilih Region">
                    <option value="" disabled>Pilih Region</option>
                    @foreach ($regions as $region)
                        <option value="{{ strtolower($region->nama_region) }}">{{ $region->nama_region }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select id="tipeJaringanFilter" name="tipe[]" multiple data-placeholder="Pilih Tipe Jaringan">
                    <option value="" disabled>Pilih Tipe Jaringan</option>
                    @foreach ($tipeJaringan as $tipe)
                    <option value="{{ strtolower($tipe->nama_tipe) }}">{{ $tipe->nama_tipe }}</option>
                    @endforeach
                </select>
            </div>

            <div class="search-bar">
                <input type="text" id="searchInput" class="custom-select" placeholder="Cari" onkeyup="searchTable()" />
            </div>
        </div>

  <!-- Table Container -->
<div class="table-container" style="overflow-x: auto; width: 100%;">
    <table id="jaringanTable" style="width: 100%; min-width: 1300px; border-collapse: collapse; table-layout: fixed; border: 1px solid #ddd;">
        <thead>
            <tr style="background-color: #f8f8f8;">
                <th style="width: 5%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">No</th>
                <th style="width: 8%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Region</th>
                <th style="width: 10%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Tipe Jaringan</th>
                <th style="width: 15%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Segmen</th>
                <th style="width: 12%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Jartatup/Jartaplok</th>
                <th style="width: 13%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Mainlink/Backuplink</th>
                <th style="width: 7%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Panjang</th>
                <th style="width: 7%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Panjang Drawing</th>
                <th style="width: 7%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Jumlah Core</th>
                <th style="width: 10%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Jenis Kabel</th>
                <th style="width: 8%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Tipe Kabel</th>
                <th style="width: 7%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Status</th>
                <th style="width: 12%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jaringan as $data)
            <tr data-id="{{ $data->id_jaringan }}">
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->region ? $data->region->nama_region : 'Region Tidak Ditemukan' }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->tipe ? $data->tipe->nama_tipe : 'Tipe Tidak Ditemukan' }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->segmen }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->jartatup_jartaplok }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->mainlink_backuplink }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->panjang }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->panjang_drawing }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->jumlah_core }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->jenis_kabel }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->tipe_kabel }}</td>
                <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">{{ $data->status }}</td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: nowrap;">
                        <button class="detail-button" onclick="lihatDetail(this)" style="background-color: #9697D6; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-right: 5px;">
                            <i class="fa-solid fa-eye"></i> 
                        </button>
                        <button class="edit-btn" onclick="editJaringan('{{ $data->id_jaringan }}')" style="background-color: #4f52ba; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-right: 5px;">
                            <i class="fa-solid fa-pen"></i> 
                        </button>
                        <button class="delete-btn" onclick="deleteJaringan('{{ $data->id_jaringan }}')" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                            <i class="fa-solid fa-trash-can"></i> 
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
            <tr id="noDataMessage" style="display: none;">
                <td colspan="13" style="text-align: center; padding: 10px;">Data Jaringan Tidak Tersedia</td>
            </tr>
        </tbody>
    </table>
</div>
</div>

<div id="addJaringanModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddJaringanModal()">×</button>
        <h2 class="modal-title">Tambah Jaringan Baru</h2>
        <form id="addJaringanForm" action="{{ route('jaringan.store') }}" method="POST">
            @csrf
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="RO">Region</label>
                        <select id="RO" name="RO" required>
                            <option value="">Pilih Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->kode_region }}" data-kode="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tipeJaringanAdd">Tipe Jaringan</label>
                        <select id="tipeJaringanAdd" name="tipe_jaringan" required>
                            <option value="">Pilih Tipe Jaringan</option>
                            @foreach($tipeJaringan as $tipe)
                                <option value="{{ $tipe->kode_tipe }}" data-kode="{{ $tipe->kode_insan }}">{{ $tipe->nama_tipe }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="segmen">Segmen</label>
                        <input type="text" id="segmen" name="segmen" required>
                    </div>

                    <div class="form-group">
                        <label for="jartatup_jartaplok">Jartatup/Jartaplok</label>
                        <select id="jartatup_jartaplok" name="jartatup_jartaplok" required>
                            <option value="">Pilih Jartatup/Jartaplok</option>
                            <option value="Jartatup">Jartatup</option>
                            <option value="Jartaplok">Jartaplok</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mainlink_backuplink">Mainlink/Backuplink</label>
                        <input type="text" id="mainlink_backuplink" name="mainlink_backuplink" required>
                    </div>
                    <div class="form-group">
                        <label for="panjang">Panjang</label>
                        <input type="text" id="panjang" name="panjang" required>
                    </div>
                    <div class="form-group">
                        <label for="panjang_drawing">Panjang Drawing</label>
                        <input type="text" id="panjang_drawing" name="panjang_drawing" required>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_core">Jumlah Core</label>
                        <input type="text" id="jumlah_core" name="jumlah_core" required>
                    </div>

                   
                    <div class="form-group">
                        <label for="jenis_kabel">Jenis Kabel</label>
                        <select id="jenis_kabel" name="jenis_kabel" required>
                            <option value="">Pilih Jenis Kabel</option>
                            <option value="Kabel Tanah">Kabel Tanah</option>
                            <option value="Kabel Udara">Kabel Udara</option>
                            <option value="Mix Kabel Tanah & Udara">Mix Kabel Tanah & Udara</option>
                        </select>
                    </div>

                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="tipe_kabel">Tipe Kabel</label>
                        <input type="text" id="tipe_kabel" name="tipe_kabel" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" id="status" name="status" required>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan" required>
                    </div>

                    <div class="form-group">
                        <label for="keterangan_2">Keterangan 2</label>  
                        <input type="text" id="keterangan_2" name="keterangan_2" required>      
                    </div>

                    <div class="form-group">
                        <label for="kode_site_insan">Kode Site Insan</label>
                        <input type="text" id="kode_site_insan" name="kode_site_insan" placeholder="Kode Site Insan" required readonly>
                    </div>

                    <div class="form-group">
                        <label for="dci_eqx">DCI/EQX</label>
                        <input type="text" id="dci_eqx" name="dci_eqx" required>
                    </div>

                    <div class="form-group">
                        <label for="update">Update</label>
                        <input type="text" id="update" name="update" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="route">Route</label>
                        <input type="text" id="route" name="route" required>
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="edit-button">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="editJaringanModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeEditJaringanModal()">&times;</button>
        <h2 class="modal-title">Edit Jaringan</h2>
        <form id="editJaringanForm">
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="editRO">Region</label>
                        <select id="editRO" name="RO" required>
                            <option value="">Pilih Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editTipeJaringan">Tipe Jaringan</label>
                        <select id="editTipeJaringan" name="tipe_jaringan" required>
                            <option value="">Pilih Tipe Jaringan</option>
                            @foreach($tipeJaringan as $tipe)
                                <option value="{{ $tipe->kode_tipe }}">{{ $tipe->nama_tipe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSegmen">Segmen</label>
                        <input type="text" id="editSegmen" name="segmen" required>
                    </div>
                    <div class="form-group">
                        <label for="editJartatupJartaplok">Jartatup/Jartaplok</label>
                        <select id="editJartatupJartaplok" name="jartatup_jartaplok" required>
                            <option value="">Pilih Jartatup/Jartaplok</option>
                            <option value="Jartatup">Jartatup</option>
                            <option value="Jartaplok">Jartaplok</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editMainlinkBackuplink">Mainlink/Backuplink</label>
                        <input type="text" id="editMainlinkBackuplink" name="mainlink_backuplink" required>
                    </div>
                    <div class="form-group">
                        <label for="editPanjang">Panjang</label>
                        <input type="text" id="editPanjang" name="panjang" required>
                    </div>
                    <div class="form-group">
                        <label for="editPanjangDrawing">Panjang Drawing</label>
                        <input type="text" id="editPanjangDrawing" name="panjang_drawing" required>
                    </div>
                    <div class="form-group">
                        <label for="editJumlahCore">Jumlah Core</label>
                        <input type="text" id="editJumlahCore" name="jumlah_core" required>
                    </div>
                    <div class="form-group">
                        <label for="editJenisKabel">Jenis Kabel</label>
                        <select id="editJenisKabel" name="jenis_kabel" required>
                            <option value="">Pilih Jenis Kabel</option>
                            <option value="Kabel Tanah">Kabel Tanah</option>
                            <option value="Kabel Udara">Kabel Udara</option>
                            <option value="Mix Kabel Tanah & Udara">Mix Kabel Tanah & Udara</option>
                        </select>
                    </div>
                </div>
                <div class="right-column">
                    <div class="form-group">
                        <label for="editTipeKabel">Tipe Kabel</label>
                        <input type="text" id="editTipeKabel" name="tipe_kabel" required>
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <input type="text" id="editStatus" name="status" required>
                    </div>
                    <div class="form-group">
                        <label for="editKeterangan">Keterangan</label>
                        <input type="text" id="editKeterangan" name="keterangan" required>
                    </div>
                    <div class="form-group">
                        <label for="editKeterangan_2">Keterangan 2</label>
                        <input type="text" id="editKeterangan_2" name="keterangan_2" required>
                    </div>
                    <div class="form-group">
                        <label for="editKodeSiteInsan">Kode Site Insan</label>
                        <input type="text" id="editKodeSiteInsan" name="kode_site_insan" required>
                    </div>
                    <div class="form-group">
                        <label for="editDCI_EQX">DCI/EQX</label>
                        <input type="text" id="editDCI_EQX" name="dci_eqx" required>
                    </div>
                    <div class="form-group">
                        <label for="editUpdate">Update</label>
                        <input type="text" id="editUpdate" name="update" required>
                    </div>
                    <div class="form-group">
                        <label for="editRoute">Route</label>
                        <input type="text" id="editRoute" name="route" required>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <button type="button" class="edit-button" onclick="updateJaringan()">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk Detail Jaringan -->
<div id="detailJaringanModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeDetailModal()">×</button>
        <h2 style="text-align: center;">Detail Jaringan</h2>
        <div class="form-container">
            <div class="left-column">
                <div class="form-group">
                    <label>Region</label>
                    <input type="text" id="regionView" readonly />
                </div>
                <div class="form-group">
                    <label>Tipe Jaringan</label>
                    <input type="text" id="tipeJaringanView" readonly />
                </div>
                <div class="form-group">
                    <label>Segmen</label>
                    <input type="text" id="segmenView" readonly />
                </div>
                <div class="form-group">
                    <label>Jartatup/Jartaplok</label>
                    <input type="text" id="jartatupView" readonly />
                </div>
                <div class="form-group">
                    <label>Mainlink/Backuplink</label>
                    <input type="text" id="mainlinkView" readonly />
                </div>
                <div class="form-group">
                    <label>Panjang</label>
                    <input type="text" id="panjangView" readonly />
                </div>
                <div class="form-group">
                    <label>Panjang Drawing</label>
                    <input type="text" id="panjangDrawingView" readonly />
                </div>
                <div class="form-group">
                    <label>Jumlah Core</label>
                    <input type="text" id="jumlahCoreView" readonly />
                </div>
            </div>
            <div class="right-column">
                <div class="form-group">
                    <label>Jenis Kabel</label>
                    <input type="text" id="jenisKabelView" readonly />
                </div>
                <div class="form-group">
                    <label>Tipe Kabel</label>
                    <input type="text" id="tipeKabelView" readonly />
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <input type="text" id="statusView" readonly />
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" id="keteranganView" readonly />
                </div>
                <div class="form-group">
                    <label>Keterangan 2</label>
                    <input type="text" id="keterangan2View" readonly />
                </div>
                <div class="form-group">
                    <label>Kode Site Insan</label>
                    <input type="text" id="kodeSiteInsanView" readonly />
                </div>
                <div class="form-group">
                    <label>DCI-EQX</label>
                    <input type="text" id="dciEqxView" readonly />
                </div>
                <div class="form-group">
                    <label>Update</label>
                    <input type="text" id="updateView" readonly />
                </div>
                <div class="form-group">
                    <label>Route</label>
                    <input type="text" id="routeView" readonly />
                </div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Aksi</th>
                    <th>Tanggal Perubahan</th>
                </tr>
            </thead>
            <tbody id="historiTableBody">
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Export -->
<div id="exportModal" class="jaringan-modal-overlay" style="display: none;">
    <div class="jaringan-modal-content">
        <span class="jaringan-modal-close-btn" onclick="closeExportModal()">&times;</span>
        <h2>Pilih Opsi Ekspor</h2>
        <form id="exportForm">
            <div>
                <label>
                    <input type="checkbox" name="export_option" value="all" id="exportAll"> 
                    <span style="margin-left: 10px;">Semua Data</span>
                </label>
            </div>
            <div style="margin-top: 15px;">
                <label for="roSelect">Pilih Region:</label>
                <select id="roSelect" name="ro_option" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="">Pilih Region</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="jaringan-modal-footer">
                <button type="button" onclick="exportData()" class="jaringan-export-button">Ekspor</button>
            </div>
        </form>
    </div>
    <!-- Indikator Loading -->
    <div id="loadingIndicator" style="display: none; text-align: center; margin-top: 20px;">
        <p>Loading... Mohon tunggu.</p>
        <img src="path/to/loading.gif" alt="Loading" /> <!-- Ganti dengan path ke gambar loading Anda -->
    </div>
</div>

<script src="{{ asset('path/to/your/script.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
    $('#roFilter, #tipeJaringanFilter').select2({
        placeholder: function() {
            return $(this).data('placeholder');
        },
        allowClear: true
    });

    // Event listener untuk menutup dropdown setelah memilih
    $('#roFilter, #tipeJaringanFilter').on('change', function() {
        const selectedValues = $(this).val();
        if (selectedValues.length > 0) {
            $(this).select2('close'); // Menutup dropdown
        }
        filterTable(); // Panggil fungsi filter
    });

    function filterTable() {
        const roFilter = $('#roFilter').val() || [];
        const tipeFilter = $('#tipeJaringanFilter').val() || [];
        const rows = $("#jaringanTable tbody tr");
        let hasVisibleRow = false;

        rows.each(function() {
            const row = $(this);
            const roCell = row.find('td').eq(1).text().toLowerCase();
            const tipeJaringanCell = row.find('td').eq(2).text().toLowerCase();

            const matchesRO = roFilter.length === 0 || roFilter.includes(roCell);
            const matchesTipe = tipeFilter.length === 0 || tipeFilter.includes(tipeJaringanCell);

            if (matchesRO && matchesTipe) {
                row.show();
                hasVisibleRow = true;
            } else {
                row.hide();
            }
        });

        $('#noDataMessage').toggle(!hasVisibleRow);

        console.log('RO Filter:', roFilter);
        console.log('Tipe Filter:', tipeFilter);
        console.log('Row Tipe Jaringan Cell:', tipeJaringanCell);
    }

    $('#RO, #tipeJaringanAdd').change(function() {
        updateKodeSiteInsan(); // Panggil fungsi untuk mengupdate kode_site_insan
    });

    function updateKodeSiteInsan() {
        const kodeRegion = $('#RO option:selected').data('kode'); // Ambil kode region
        const kodeInsan = $('#tipeJaringanAdd option:selected').data('kode'); // Ambil kode insan

        if (kodeRegion && kodeInsan) {
            // Buat kode site insan dasar
            let baseKodeSiteInsan = `${kodeRegion}${kodeInsan}`; // Contoh: BTM + BB

            // Ambil urutan terakhir dari database
            getLastNumberForKodeSiteInsan(baseKodeSiteInsan).then(lastNumber => {
                // Tambahkan angka ke kode site insan
                const kodeSiteInsan = `${baseKodeSiteInsan}${lastNumber}`;
                $('#kode_site_insan').val(kodeSiteInsan); // Isi field kode_site_insan
            });
        } else {
            $('#kode_site_insan').val(''); // Kosongkan jika tidak ada pilihan
        }
    }

    // Fungsi untuk mendapatkan angka terakhir yang digunakan untuk kode_site_insan
    function getLastNumberForKodeSiteInsan(baseKode) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/get-last-kode-site-insan', // Endpoint untuk mendapatkan urutan terakhir
                method: 'GET',
                data: { baseKode: baseKode },
                success: function(response) {
                    resolve(response.lastNumber); // Kembalikan angka terakhir
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching last number:', error);
                    resolve(1); // Jika error, mulai dari 1
                }
            })});
    }});
  
    function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll("#jaringanTable tbody tr");

        rows.forEach(row => {
            const cells = row.getElementsByTagName("td");
            let matchesSearch = false;
            
            for (let i = 0; i < cells.length; i++) {
                if (cells[i].textContent.toLowerCase().includes(filter)) {
                    matchesSearch = true;
                    break;
                }
            }

            if (matchesSearch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    function openAddJaringanModal() {
        document.getElementById("addJaringanModal").style.display = "flex";
    }

    function closeAddJaringanModal() {
        document.getElementById("addJaringanModal").style.display = "none";
    }

    // Optional: close modal when clicking outside of the modal content
    window.onclick = function(event) {
        const modal = document.getElementById("addJaringanModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };

    function showSwal(type, message) {
        if (type === 'success') {
            swal({
                title: "Berhasil!",
                text: message,
                type: "success",
                button: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-primary"
                }
            });
        } else if (type === 'error') {
            swal({
                title: "Error!",
                text: message,
                type: "error",
                button: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-danger"
                }
            });
        }
    }

    // Fungsi untuk edit jaringan
    function editJaringan(id_jaringan) {
        $.ajax({
            url: '{{ url('/edit-jaringan') }}/' + id_jaringan,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    // Isi form dengan data yang diterima
                    $('#editRO').val(response.data.RO);
                    $('#editTipeJaringan').val(response.data.tipe_jaringan);
                    $('#editSegmen').val(response.data.segmen);
                    $('#editJartatupJartaplok').val(response.data.jartatup_jartaplok);
                    $('#editMainlinkBackuplink').val(response.data.mainlink_backuplink);
                    $('#editPanjang').val(response.data.panjang);
                    $('#editPanjangDrawing').val(response.data.panjang_drawing);
                    $('#editJumlahCore').val(response.data.jumlah_core);
                    $('#editJenisKabel').val(response.data.jenis_kabel);
                    $('#editTipeKabel').val(response.data.tipe_kabel);
                    $('#editStatus').val(response.data.status);
                    $('#editKeterangan').val(response.data.keterangan);
                    $('#editKeterangan_2').val(response.data.keterangan_2);
                    $('#editKodeSiteInsan').val(response.data.kode_site_insan);
                    $('#editDCI_EQX').val(response.data.dci_eqx);
                    $('#editUpdate').val(response.data.update);
                    $('#editRoute').val(response.data.route);
                    $('#editJaringanForm').data('id_jaringan', id_jaringan); // Simpan ID jaringan
                    // Tampilkan modal edit
                    openEditJaringanModal();
                } else {
                    Swal.fire({
                        title: "Gagal!",
                        text: response.message || "Terjadi kesalahan saat mengambil data jaringan.",
                        icon: "error",
                        confirmButtonText: "OK",
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "Terjadi kesalahan saat menghubungi server.",
                    icon: "error",
                    confirmButtonText: "OK",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            }
        });
    }

    function updateJaringan() {
        var id_jaringan = $('#editJaringanForm').data('id_jaringan'); // Ambil ID jaringan
        var formData = $('#editJaringanForm').serialize();
        closeEditJaringanModal();
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin mengupdate data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, update!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'swal2-confirm2', // Kelas untuk tombol konfirmasi
                cancelButton: 'swal2-confirm' // Kelas untuk tombol batal
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('/update-jaringan') }}/' + id_jaringan,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            closeEditJaringanModal(); // Tutup modal setelah berhasil
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Data jaringan berhasil diupdate.",
                                icon: "success",
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: "OK",
                                customClass: {
                                    confirmButton: 'swal2-confirm2'
                                }
                            }).then(() => {
                                location.reload(); // Muat ulang halaman untuk melihat perubahan
                            });
                        } else {
                            closeEditJaringanModal(); // Tutup modal setelah berhasil
                            Swal.fire({
                                title: "Gagal!",
                                text: response.message || "Gagal mengupdate data jaringan.",
                                icon: "error",
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: "OK"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        closeEditJaringanModal(); // Tutup modal setelah berhasil
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan saat menghubungi server.",
                            icon: "error",
                            confirmButtonColor: '#4f52ba',
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    }

    function deleteJaringan(id_jaringan) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
            buttonsStyling: false,
            customClass: {
                confirmButton: 'swal2-confirm', // Gunakan kelas CSS untuk tombol hapus
                cancelButton: 'swal2-cancel' // Gunakan kelas CSS untuk tombol batal
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/delete-jaringan/${id_jaringan}`, // Menggunakan id_jaringan
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Data berhasil dihapus.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'swal2-confirm2' // Gunakan kelas CSS untuk tombol OK
                                }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Gagal menghapus data.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    }

    function storeJaringan() {
        // Reset form
        $('#addJaringanForm')[0].reset();
        
        // Tampilkan modal
        document.getElementById("addJaringanModal").style.display = "flex";
    }

    $(document).ready(function() {
        $('#addJaringanForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = {
                RO: $('#RO').val(),
                tipe_jaringan: $('#tipeJaringanAdd').val(),
                segmen: $('#segmen').val(),
                jartatup_jartaplok: $('#jartatup_jartaplok').val(),
                mainlink_backuplink: $('#mainlink_backuplink').val(),
                panjang: $('#panjang').val(),
                panjang_drawing: $('#panjang_drawing').val(),
                jumlah_core: $('#jumlah_core').val(),
                jenis_kabel: $('#jenis_kabel').val(),
                tipe_kabel: $('#tipe_kabel').val(),
                status: $('#status').val(),
                keterangan: $('#keterangan').val(),
                keterangan_2: $('#keterangan_2').val(),
                kode_site_insan: $('#kode_site_insan').val(),
                dci_eqx: $('#dci_eqx').val(),
                update: $('#update').val(),
                route: $('#route').val()
            };
            closeAddJaringanModal();
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menambahkan data ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'swal2-confirm2', // Kelas untuk tombol konfirmasi
                    cancelButton: 'swal2-confirm' // Kelas untuk tombol batal
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("jaringan.store") }}',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Data jaringan berhasil ditambahkan.",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                    customClass: {
                                    confirmButton: 'swal2-confirm2' // Gunakan kelas CSS untuk tombol OK
                                }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: response.message,
                                icon: "error",
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: "OK"
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Tambahkan ini untuk melihat detail kesalahan
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan saat mengunggah file. Lihat konsol untuk detail.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    });
});

    function openEditJaringanModal() {
        document.getElementById('editJaringanModal').style.display = 'flex';
    }

    function closeEditJaringanModal() {
        document.getElementById('editJaringanModal').style.display = 'none';
    }

    function lihatDetail(button) {
        const row = button.closest('tr');
        const id_jaringan = row.getAttribute('data-id');

        // Ambil detail jaringan
        fetch(`/jaringan/${id_jaringan}/lihat-detail`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const jaringan = data.data;

                    // Isi data ke elemen yang sesuai
                    $('#regionView').val(jaringan.region);
                    $('#tipeJaringanView').val(jaringan.tipe_jaringan);
                    $('#segmenView').val(jaringan.segmen);
                    $('#jartatupView').val(jaringan.jartatup_jartaplok);
                    $('#mainlinkView').val(jaringan.mainlink_backuplink);
                    $('#panjangView').val(jaringan.panjang);
                    $('#panjangDrawingView').val(jaringan.panjang_drawing);
                    $('#jumlahCoreView').val(jaringan.jumlah_core);
                    $('#jenisKabelView').val(jaringan.jenis_kabel);
                    $('#tipeKabelView').val(jaringan.tipe_kabel);
                    $('#statusView').val(jaringan.status);
                    $('#keteranganView').val(jaringan.ket);
                    $('#keterangan2View').val(jaringan.ket2);
                    $('#kodeSiteInsanView').val(jaringan.kode_site_insan);
                    $('#dciEqxView').val(jaringan.dci_eqx);
                    $('#updateView').val(jaringan.update);
                    $('#routeView').val(jaringan.route);

                    // Tampilkan modal
                    document.getElementById("detailJaringanModal").style.display = "flex";

                    // Ambil data histori jaringan
                    fetch(`/histori/jaringan/${id_jaringan}`)
                        .then(response => response.json())
                        .then(historiData => {
                            let historiRows = '';
                            if (historiData.success && historiData.data.length > 0) {
                                historiData.data.forEach(histori => {
                                    const tanggal = new Date(histori.tanggal_perubahan);
                                    const options = {
                                        weekday: 'long', day: '2-digit', month: 'long', year: 'numeric',
                                        hour: '2-digit', minute: '2-digit'
                                    };
                                    const formattedTanggal = tanggal.toLocaleDateString('id-ID', options).replace('pukul', 'pada pukul');

                                    historiRows += `
                                        <tr>
                                            <td style="width: 50%; text-align: justify;">${histori.aksi}</td>
                                            <td style="width: 50%;">${formattedTanggal}</td>
                                        </tr>
                                    `;
                                });
                            } else {
                                historiRows = `
                                    <tr>
                                        <td colspan="2" style="text-align: center;">Tidak ada histori tersedia</td>
                                    </tr>
                                `;
                            }

                            // Isi tabel histori
                            $('#historiTableBody').html(historiRows);
                        });
                }
            });
    }

    // Fungsi untuk menutup modal
    function closeDetailModal() {
        document.getElementById("detailJaringanModal").style.display = "none";
    }

    function importData() {
        // Logika untuk mengimpor data
        // Misalnya, membuka dialog untuk memilih file
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.csv, .xlsx, .xls'; // Format yang diterima
        input.onchange = (event) => {
            const file = event.target.files[0];
            if (file) {
                // Lakukan sesuatu dengan file, seperti mengupload
                console.log('File yang dipilih:', file);
                
                // Membuat FormData untuk mengirim file
                const formData = new FormData();
                formData.append('file', file);

                // Mengunggah file ke server
                $.ajax({
                    url: '{{ route("jaringan.import") }}', // Pastikan ini sesuai dengan route yang benar
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: formData,
                    processData: false, // Jangan proses data
                    contentType: false, // Jangan set content type
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Data berhasil diimpor.",
                                icon: "success",
                                confirmButtonText: "OK",
                                customClass: {
                                    confirmButton: 'swal2-confirm2' // Gunakan kelas CSS untuk tombol OK
                                }
                            }).then(() => {
                                location.reload(); // Muat ulang halaman untuk melihat perubahan
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: response.message,
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Tambahkan ini untuk melihat detail kesalahan
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan saat mengunggah file. Lihat konsol untuk detail.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        };
        input.click(); // Membuka dialog file
    }

    function showExportModal() {
        document.getElementById('exportModal').style.display = 'flex'; // Menggunakan flex untuk memusatkan
    }

    function closeExportModal() {
        document.getElementById('exportModal').style.display = 'none'; // Menyembunyikan modal
    }

    function exportData() {
        const isAllDataChecked = document.getElementById('exportAll').checked; // Cek apakah checkbox dicentang
        const selectedRegion = document.getElementById('roSelect').value; // Ambil nilai dari dropdown region
        const exportOption = isAllDataChecked ? 'all' : 'unique'; // Tentukan opsi ekspor berdasarkan checkbox

        console.log('Opsi ekspor yang dipilih:', exportOption); // Log opsi yang dipilih
        console.log('Region yang dipilih:', selectedRegion); // Log region yang dipilih

        // Sembunyikan modal ekspor
        closeExportModal();

        // Tampilkan indikator loading
        document.getElementById('loadingIndicator').style.display = 'block';

        // Mengirim permintaan ke server untuk mengekspor data
        $.ajax({
            url: '{{ route("jaringan.export") }}', // Pastikan rute ini sesuai
            type: 'POST', // Pastikan ini adalah POST
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { option: exportOption, region: selectedRegion }, // Kirim region yang dipilih
            success: function(response) {
                console.log('Response dari server:', response); // Log response dari server
                if (response.success) {
                    // Jika berhasil, arahkan ke URL file PDF yang dihasilkan
                    window.location.href = response.file_url; // Mengunduh PDF
                } else {
                    Swal.fire('Gagal!', response.message, 'error'); // Tampilkan pesan kesalahan yang lebih spesifik
                }
            },
            error: function(xhr) {
                console.error('Kesalahan saat mengirim permintaan:', xhr); // Log kesalahan saat permintaan gagal
                const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat mengekspor data.';
                Swal.fire('Error!', errorMessage, 'error'); // Tampilkan pesan kesalahan yang lebih spesifik
            },
            complete: function() {
                // Sembunyikan indikator loading setelah proses selesai
                document.getElementById('loadingIndicator').style.display = 'none';
            }
        });
    }
</script>
@endsection