@extends('layouts.sidebar')

@section('content')
    <!-- Pastikan jQuery dan SweetAlert2 dimuat -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Meta tag CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            overflow-x: auto;
            border-radius: 8px;
            position: relative;
        }
        
        table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            table-layout: fixed;
        }
        
        th, td {
            padding: 12px !important;
            text-align: center !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
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

        /* Atur lebar maksimum dan izinkan pembungkusan teks untuk kolom Segmen */
        td.segmen {
            width: 200px; /* Atur lebar kolom sesuai kebutuhan */
            white-space: normal; /* Izinkan pembungkusan teks */
            word-wrap: break-word; /* Membungkus kata jika terlalu panjang */
            word-break: break-word; /* Memastikan pembungkusan kata */
            overflow: visible; /* Pastikan tidak ada pemotongan teks */
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

        .add-button {
            background-color: #4f52ba;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-button:hover {
            background-color: #3e41a1;
        }
    </style>

<div class="main">
    <div class="container">
        <div class="text-right">
            <button class="add-button" onclick="openAddJaringanModal()">Tambah Jaringan</button>
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
            <select id="tipeJaringanFilter" onchange="filterByTipeJaringan()">
                <option value="">Pilih Tipe Jaringan</option>
                @foreach ($tipeJaringanList as $tipe)
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
                        <th style="width: 250px;">Segmen</th>
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
                        <td>{{ $data->tipe_jaringan }}</td>
                        <td class="segmen">{{ $data->segmen }}</td>
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
                        <label for="roAdd">RO</label>
                        <select id="roAdd" name="ro" required>
                            <option value="">Pilih RO</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->nama_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kode_site_insan">Kode Site Insan</label>
                        <input type="text" id="kode_site_insan" name="kode_site_insan" required>
                    </div>

                    <div class="form-group">
                        <label for="tipeJaringanAdd">Tipe Jaringan</label>
                        <select id="tipeJaringanAdd" name="tipe_jaringan" required>
                            <option value="">Pilih Tipe Jaringan</option>
                            @foreach($tipeJaringanList as $tipe)
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

<script>
    function filterTable() {
        const roFilter = document.getElementById("roFilter").value.toLowerCase();
        const rows = document.querySelectorAll("#jaringanTable tbody tr");

        rows.forEach(row => {
            const roCell = row.querySelector(".ro").textContent.toLowerCase();
            const matchesRO = roFilter === "" || roCell.includes(roFilter);

            if (matchesRO) {
                row.style.display = ""; // Menampilkan baris
            } else {
                row.style.display = "none"; // Menyembunyikan baris
            }
        });
    }

    function filterByTipeJaringan() {
    const tipeFilter = document.getElementById("tipeJaringanFilter").value.toLowerCase();
    const rows = document.querySelectorAll("#jaringanTable tbody tr");

    console.log("Tipe Filter yang dipilih:", tipeFilter); // Debugging log

    rows.forEach(row => {
        const tipeJaringanCell = row.cells[3].textContent.toLowerCase(); // Pastikan indeks sesuai dengan kolom "Tipe Jaringan"
        console.log("Tipe Jaringan pada row:", tipeJaringanCell); // Debugging log

        if (tipeFilter === "" || tipeJaringanCell.includes(tipeFilter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
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
    $.get(`/jaringan/${id_jaringan}/edit`, function(response) {
        if (response.success) {
            const jaringan = response.jaringan;

            // Reset form terlebih dahulu
            $('#addJaringanForm')[0].reset();

            // Isi form dengan data yang ada
            $('#roAdd').val(jaringan.ro).trigger('change');
            $('#kode_site_insan').val(jaringan.kode_site_insan);
            $('#tipeJaringanAdd').val(jaringan.tipe_jaringan);
            $('#segmen').val(jaringan.segmen);
            $('#jartatup_jartaplok').val(jaringan.jartatup_jartaplok);
            $('#mainlink_backuplink').val(jaringan.mainlink_backuplink);
            $('#panjang').val(jaringan.panjang);
            $('#panjang_drawing').val(jaringan.panjang_drawing);
            $('#jumlah_core').val(jaringan.jumlah_core);
            $('#jenis_kabel').val(jaringan.jenis_kabel);
            $('#tipe_kabel').val(jaringan.tipe_kabel);
            $('#travelling_time').val(jaringan.travelling_time);
            $('#restoration_time').val(jaringan.restoration_time);
            $('#total_corrective_time').val(jaringan.total_corrective_time);

            // Hapus input hidden ID jika ada sebelumnya
            $('#jaringan-id-input').remove();

            // Tambahkan ID ke form untuk keperluan update
            $('#addJaringanForm').append(`<input type="hidden" id="jaringan-id-input" name="id_jaringan" value="${jaringan.id_jaringan}">`);

            // Ubah judul modal dan tombol
            $('h2').text('Edit Jaringan');
            $('.add-button[type="submit"]').text('Update');

            // Tampilkan modal setelah data diisi
            $('#addJaringanModal').modal('show');
        } else {
            swal({
                title: "Error!",
                text: response.message || "Gagal mengambil data jaringan.",
                type: "error",
                button: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-danger"
                }
            });
        }
    }).fail(function(xhr) {
        console.error('Edit error details:', xhr.responseText);
        swal({
            title: "Error!",
            text: "Terjadi kesalahan saat mengambil data jaringan.",
            type: "error",
            button: {
                text: "OK",
                value: true,
                visible: true,
                className: "btn btn-danger"
            }
        });
    });
}


function deleteJaringan(id_jaringan) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
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
                        Swal.fire(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
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

</script>
@endsection