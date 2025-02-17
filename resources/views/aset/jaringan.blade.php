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
        
        .dropdown-container {
            display: flex;
            gap: 20px;
            align-items: center;
            width: 100%;
        }
        
        .dropdown-container > * {
            flex: 1;
        }
        
        select, .search-bar input {
            width: 100%;
            font-size: 12px;
            padding: 12px 12px;
            border: 1px solid #4f52ba;
            border-radius: 5px;
            background-color: #fff;
            transition: border-color 0.3s;
        }
        
        .search-bar input {
            outline: none;
        }
        
        select:focus, .search-bar input:focus {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
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
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 500px;
            position: relative;
        }

        .modal-content h2 {
            color: #595959;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #595959;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-weight: normal;
            background-color: #fff;
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
            margin-top: 20px;
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
    </style>

<div class="main">
    <div class="container">
        <div class="text-right">
            <button class="add-button" onclick="storeJaringan()">Tambah Jaringan</button>
        </div>
        
        <div class="dropdown-container">
            <!-- Dropdown RO -->
            <select id="roFilter" onchange="filterTable()">
                <option value="">Pilih RO</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->nama_region }}">{{ $region->nama_region }}</option>
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

        <!-- Table Data -->
        <div class="table-container">
            <table id="jaringanTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 100px;">RO</th>
                        <th style="width: 100px;">Kode Site Insan</th>
                        <th style="width: 100px;">Tipe Jaringan</th>
                        <th style="width: 150px;">Segmen</th>
                        <th style="width: 100px;">Panjang</th>
                        <th style="width: 120px;">Panjang Drawing</th>
                        <th style="width: 100px;">Jumlah Core</th>
                        <th style="width: 100px;">Jenis Kabel</th>
                        <th style="width: 100px;">Tipe Kabel</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jaringan as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="ro">{{ $data->RO }}</td>
                        <td>{{ $data->kode_site_insan }}</td>
                        <td>{{ $data->tipe ? $data->tipe->nama_tipe : 'Tipe tidak ditemukan' }}</td>
                        <td>{{ $data->segmen }}</td>
                        <td>{{ $data->panjang }}</td>
                        <td>{{ $data->panjang_drawing }}</td>
                        <td>{{ $data->jumlah_core }}</td>
                        <td>{{ $data->jenis_kabel }}</td>
                        <td>{{ $data->tipe_kabel }}</td>
                        <td>
                            <button class="edit-btn" onclick="editJaringan('{{ $data->id_jaringan }}')">Edit</button>
                            <button class="delete-btn" onclick="deleteJaringan('{{ $data->id_jaringan }}')">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addJaringanModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddJaringanModal()">×</button>
        <h2>Tambah Jaringan Baru</h2>
        <form id="addJaringanForm" action="{{ route('jaringan.store') }}" method="POST">
            @csrf
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="RO">RO</label>
                        <select id="RO" name="RO" required>
                            <option value="">Pilih RO</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->nama_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kode_site_insan">Kode Site Insan</label>
                        <input type="text" id="kode_site_insan" name="kode_site_insan" placeholder="Kode Site Insan" required>
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
                
                </div>

                <div class="right-column">
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
                        <input type="text" id="jenis_kabel" name="jenis_kabel" required>
                    </div>

                    <div class="form-group">
                        <label for="tipe_kabel">Tipe Kabel</label>
                        <input type="text" id="tipe_kabel" name="tipe_kabel" required>
                    </div>

                    <div class="form-group">
                        <label for="travelling_time">Travelling Time</label>
                        <input type="text" id="travelling_time" name="travelling_time" required>
                    </div>

                    <div class="form-group">
                        <label for="restoration_time">Restoration Time</label>
                        <input type="text" id="restoration_time" name="restoration_time" required>
                    </div>

                    <div class="form-group">
                        <label for="total_corrective_time">Total Corrective Time</label>
                        <input type="text" id="total_corrective_time" name="total_corrective_time" required>
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
        <button class="modal-close-btn" onclick="closeEditJaringanModal()">×</button>
        <h2>Edit Jaringan</h2>
        <form id="editJaringanForm">
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="editRO">RO</label>
                        <select id="editRO" name="RO" required>
                            <option value="">Pilih RO</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->nama_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="editKodeSiteInsan">Kode Site Insan</label>
                        <input type="text" id="editKodeSiteInsan" name="kode_site_insan" required>
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
                </div>

                        <div class="right-column">
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
                                <input type="text" id="editJenisKabel" name="jenis_kabel" required>
                            </div>

                            <div class="form-group">
                                <label for="editTipeKabel">Tipe Kabel</label>
                                <input type="text" id="editTipeKabel" name="tipe_kabel" required>
                            </div>

                            <div class="form-group">
                                <label for="editTravellingTime">Travelling Time</label>
                                <input type="text" id="editTravellingTime" name="travelling_time" required>
                            </div>

                            <div class="form-group">
                                <label for="editRestorationTime">Restoration Time</label>
                                <input type="text" id="editRestorationTime" name="restoration_time" required>
                            </div>

                            <div class="form-group">
                                <label for="editTotalCorrectiveTime">Total Corrective Time</label>
                                <input type="text" id="editTotalCorrectiveTime" name="total_corrective_time" required>
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

        rows.forEach(row => {
            const roCell = row.querySelector(".ro").textContent.toLowerCase();
            const tipeJaringanCell = row.cells[3].textContent.toLowerCase(); // Pastikan indeks sesuai dengan kolom "Tipe Jaringan"

            const matchesRO = roFilter === "" || roCell.includes(roFilter);
            const matchesTipe = tipeFilter === "" || tipeJaringanCell.includes(tipeFilter);

            if (matchesRO && matchesTipe) {
                row.style.display = ""; // Menampilkan baris
            } else {
                row.style.display = "none"; // Menyembunyikan baris
            }
        });
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
                    $('#editKodeSiteInsan').val(response.data.kode_site_insan);
                    $('#editTipeJaringan').val(response.data.tipe_jaringan);
                    $('#editSegmen').val(response.data.segmen);
                    $('#editJartatupJartaplok').val(response.data.jartatup_jartaplok);
                    $('#editMainlinkBackuplink').val(response.data.mainlink_backuplink);
                    $('#editPanjang').val(response.data.panjang);
                    $('#editPanjangDrawing').val(response.data.panjang_drawing);
                    $('#editJumlahCore').val(response.data.jumlah_core);
                    $('#editJenisKabel').val(response.data.jenis_kabel);
                    $('#editTipeKabel').val(response.data.tipe_kabel);
                    $('#editTravellingTime').val(response.data.travelling_time);
                    $('#editRestorationTime').val(response.data.restoration_time);
                    $('#editTotalCorrectiveTime').val(response.data.total_corrective_time);
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
                        confirmButtonText: "OK",
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'swal2-confirm2'
                        },
                        position: 'center'
                    }).then(() => {
                        location.reload(); // Muat ulang halaman untuk melihat perubahan
                    });
                } else {
                    Swal.fire({
                        title: "Gagal!",
                        text: response.message || "Gagal mengupdate data jaringan.",
                        icon: "error",
                        confirmButtonText: "OK",
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'swal2-confirm2'
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
                        confirmButton: 'swal2-confirm2'
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
            kode_site_insan: $('#kode_site_insan').val(),
            tipe_jaringan: $('#tipeJaringanAdd').val(),
            segmen: $('#segmen').val(),
            jartatup_jartaplok: $('#jartatup_jartaplok').val(),
            mainlink_backuplink: $('#mainlink_backuplink').val(),
            panjang: $('#panjang').val(),
            panjang_drawing: $('#panjang_drawing').val(),
            jumlah_core: $('#jumlah_core').val(),
            jenis_kabel: $('#jenis_kabel').val(),
            tipe_kabel: $('#tipe_kabel').val(),
            travelling_time: $('#travelling_time').val(),
            verification_time: $('#verification_time').val(),
            restoration_time: $('#restoration_time').val(),
            total_corrective_time: $('#total_corrective_time').val()
        };

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
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'swal2-confirm2'
                        },
                        position: 'center'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: "Gagal!",
                        text: response.message,
                        icon: "error",
                        confirmButtonText: "OK",
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'swal2-confirm2'
                        }
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
                    confirmButtonText: "OK",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'swal2-confirm2'
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

</script>
@endsection