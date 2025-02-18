@extends('layouts.sidebar')

@section('content')
    <!-- Pastikan jQuery dan SweetAlert2 dimuat -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Kemudian impor Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Meta tag CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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
        
/* -------------------------- FILTER -------------------------- */
.dropdown-container {
    display: flex;
    gap: 15px; /* Jarak antar elemen tetap */
    align-items: center;
    width: 100%;
}

/* Menyamakan ukuran dropdown dan search bar */
.dropdown-container select, 
.dropdown-container .search-bar input {
    flex: 1; /* Membuat elemen mengambil ruang yang tersedia secara proporsional */
    font-size: 12px;
    padding: 10px 12px; /* Padding lebih kecil agar lebih compact */
    border: 1px solid #4f52ba;
    border-radius: 5px;
    max-width: 1500px; 
    background-color: #fff;
    height: 40px; /* Tinggi seragam */
    appearance: none; /* Menghilangkan default styling bawaan browser */
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
}

/* Menghilangkan ikon dropdown (caret) di sebelah kanan */
.dropdown-container select {
    background-image: none !important;
    cursor: pointer;
    max-height: 300px; /* Menambahkan batas tinggi untuk memungkinkan scroll */
    overflow-y: auto; /* Menambahkan scroll vertikal */
}

/* Styling khusus untuk search bar */
.search-bar {
    flex: 1; /* Memastikan search bar mengambil ruang yang tersedia */
}

.search-bar input {
    outline: none;
    width: 100%; /* Memastikan panjang search bar sama dengan dropdown */
}

/* Fokus pada input dan select */
.dropdown-container select:focus, 
.dropdown-container .search-bar input:focus {
    border-color: #4f52ba;
    box-shadow: 0 0 4px #4f52ba;
}

/* Mengubah warna dropdown saat dipilih */
.dropdown-container select:focus {
    background-color: #fff; /* Warna tetap putih saat fokus */
}

/* Menambahkan scrollbar pada dropdown */
.dropdown-container select {
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #4f52ba #f1f1f1;
}

.dropdown-container select::-webkit-scrollbar {
    width: 8px;
}

.dropdown-container select::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

/* Mengubah warna dropdown saat dipilih */
.dropdown-container select option:checked,
.dropdown-container select option:focus,
.dropdown-container select option:hover {
    background-color: #4f52ba !important;
    color: white !important;
}




        
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
            background-color: #4f52ba;
            color: #fff;
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
            background-color: rgba(79, 82, 186, 0.2);
        }

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
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .modal-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
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
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.3);
            outline: none;
        }

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
            width: 18.75%;
            margin-top: 10px;
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

        .modal-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 20px;
            color: #666;
            transition: color 0.3s ease;
        }

        .modal-close-btn:hover {
            color: #333;
        }

        .button-container {
            display: flex;
            justify-content: right;
            gap: 10px;
            margin-top: 10px;
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

        .swal2-cancel:hover {
            background-color: rgb(46, 50, 158) !important;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>

<div class="main">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Jaringan</h3>
            <button class="add-button" onclick="storeJaringan()">Tambah Jaringan</button>
        </div>
        
        <div class="dropdown-container">
            <!-- Dropdown RO -->
            <select id="roFilter" onchange="filterTable()">
                <option value="">Pilih Region</option>
                @foreach ($regions as $region)
                <option value="{{ strtolower($region->nama_region) }}">{{ $region->nama_region }}</option>
                @endforeach
            </select>
            <!-- Dropdown Tipe Jaringan -->
            <select id="tipeJaringanFilter" onchange="filterTable()">
                <option value="">Pilih Tipe Jaringan</option>
                @foreach ($tipeJaringan as $tipe)
                    <option value="{{ $tipe->kode_tipe }}">{{ $tipe->nama_tipe }}</option>
                @endforeach
            </select>

            <!-- Search Bar -->
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
                <th style="width: 20%; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">Aksi</th>
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
                <td style="border-bottom: 1px solid #ddd; padding: 10px;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end; flex-wrap: nowrap;">
                        <button class="detail-button" onclick="lihatDetail(this)" style="background-color: #9697D6; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-right: 5px;">
                            Lihat Detail
                        </button>
                        <button class="edit-btn" onclick="editJaringan('{{ $data->id_jaringan }}')" style="background-color: #4f52ba; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-right: 5px;">
                            Edit
                        </button>
                        <button class="delete-btn" onclick="deleteJaringan('{{ $data->id_jaringan }}')" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                            Hapus
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
                                <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tipeJaringanAdd">Tipe Jaringan</label>
                        <select id="tipeJaringanAdd" name="tipe_jaringan" required>
                            <option value="">Pilih Tipe Jaringan</option>
                            @foreach($tipeJaringan as $tipe)
                                <option value="{{ $tipe->kode_tipe }}">{{ $tipe->nama_tipe }}</option>
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
                        <input type="text" id="kode_site_insan" name="kode_site_insan" placeholder="Kode Site Insan" required>
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
                <button type="submit" class="add-button">Simpan</button>
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
                <button type="button" class="add-button" onclick="updateJaringan()">Simpan</button>
            </div>
        </form>
    </div>
</div>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function filterTable() {
        const roFilter = document.getElementById("roFilter").value.toLowerCase();
        const tipeFilter = document.getElementById("tipeJaringanFilter").value.toLowerCase();
        const rows = document.querySelectorAll("#jaringanTable tbody tr");
        let hasVisibleRow = false;

        rows.forEach(row => {
            if (row.id === "noDataMessage") return; // Skip the no data message row

            const roCell = row.cells[1].textContent.toLowerCase();
            const tipeJaringanCell = row.cells[2].textContent.toLowerCase();

            const matchesRO = roFilter === "" || roCell.includes(roFilter);
            const matchesTipe = tipeFilter === "" || tipeJaringanCell.includes(tipeFilter);

            if (matchesRO && matchesTipe) {
                row.style.display = "";
                hasVisibleRow = true;
            } else {
                row.style.display = "none";
            }
        });

        // Show or hide the no data message
        document.getElementById("noDataMessage").style.display = hasVisibleRow ? "none" : "";
    }

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
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, update!',
            cancelButtonText: 'Batal'
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
                                confirmButtonText: "OK"
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
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
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
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: "OK"
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
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        
                        // Tampilkan semua pesan error
                        Object.keys(errors).forEach(function(key) {
                            errorMessage += errors[key][0] + '\n';
                        });
                        
                        Swal.fire({
                            title: "Gagal!",
                            text: errorMessage,
                            icon: "error",
                            confirmButtonColor: '#4f52ba',
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
    fetch(`/jaringan/${id_jaringan}/detail`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const jaringan = data.data;

                // Ambil histori perubahan
                fetch(`/histori/jaringan/${id_jaringan}`)
                    .then(response => response.json())
                    .then(historiData => {
                        let historiRows = '';
                        if (historiData.success && historiData.data.length > 0) {
                            historiData.data.forEach(histori => {
                                historiRows += `
                                    <tr>
                                        <td style="padding: 5px;">${histori.aksi}</td>
                                        <td style="padding: 5px;">${new Date(histori.tanggal_perubahan).toLocaleString('id-ID')}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            historiRows = `
                                <tr>
                                    <td colspan="2" style="text-align: center; padding: 5px;">Histori tidak tersedia</td>
                                </tr>
                            `;
                        }

                        // Tampilkan detail jaringan dan histori
                        Swal.fire({
                            title: 'Detail Jaringan',
                            html: `
                                <button id="closeButton" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 16px; cursor: pointer;">&times;</button>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; font-size: 0.85rem; padding: 10px;">
                                    <div>
                                        ${generateInputField('Region', jaringan.RO)}
                                        ${generateInputField('Tipe Jaringan', jaringan.tipe_jaringan)}
                                        ${generateInputField('Segmen', jaringan.segmen)}
                                        ${generateInputField('Jartatup/Jartaplok', jaringan.jartatup_jartaplok)}
                                        ${generateInputField('Mainlink/Backuplink', jaringan.mainlink_backuplink)}
                                        ${generateInputField('Panjang', jaringan.panjang)}
                                        ${generateInputField('Panjang Drawing', jaringan.panjang_drawing)}
                                        ${generateInputField('Jumlah Core', jaringan.jumlah_core)}
                                    </div>
                                    <div>
                                        ${generateInputField('Jenis Kabel', jaringan.jenis_kabel)}
                                        ${generateInputField('Tipe Kabel', jaringan.tipe_kabel)}
                                        ${generateInputField('Status', jaringan.status)}
                                        ${generateInputField('Keterangan', jaringan.keterangan)}
                                        ${generateInputField('Keterangan 2', jaringan.keterangan_2)}
                                        ${generateInputField('Kode Site Insan', jaringan.kode_site_insan)}
                                        ${generateInputField('DCI-EQX', jaringan.dci_eqx)}
                                        ${generateInputField('Update', jaringan.update)}
                                        ${generateInputField('Route', jaringan.route)}
                                    </div>
                                </div>
                                
                                <hr style="margin: 10px 0;">

                                <h4 style="font-size: 14px; text-align: center; margin-bottom: 5px;">Histori Perubahan</h4>
                                <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 5px; border-radius: 6px; background: #f8f9fa;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                                        <thead>
                                            <tr style="background: #f0f0f0;">
                                                <th style="text-align: left; padding: 5px;">Aksi</th>
                                                <th style="text-align: left; padding: 5px;">Tanggal Perubahan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="historiTableBody">
                                            ${historiRows}
                                        </tbody>
                                    </table>
                                </div>
                            `,
                            icon: 'info',
                            width: '500px',
                            showConfirmButton: false,
                            customClass: {
                                popup: 'swal2-popup-custom'
                            },
                            didOpen: () => {
                                const closeButton = document.getElementById('closeButton');
                                closeButton.addEventListener('click', () => {
                                    Swal.close();
                                });
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching histori:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat mengambil histori',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'swal2-confirm2'
                            }
                        });
                    });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'swal2-confirm2'
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengambil data',
                icon: 'error',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'swal2-confirm2'
                }
            });
        });
}

function generateInputField(label, value) {
    return `
        <div style="display: flex; flex-direction: column;">
            <label style="font-weight: bold; font-size: 0.75rem; color: #444;">${label}:</label>
            <input type="text" value="${value}" readonly class="swal2-input" style="background: #f8f9fa; border: 1px solid #ddd; border-radius: 6px; padding: 5px; font-size: 0.85rem;">
        </div>
    `;
}
</script>
@endsection