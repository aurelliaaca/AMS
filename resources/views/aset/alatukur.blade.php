@extends('layouts.sidebar')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            overflow-x: auto;
            border-radius: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            table-layout: fixed;
        }
        
        th, td {
            padding: 12px;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #4f52ba;
            color: #fff;
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
    </style>

<div class="main">
    <div class="container">
        <div class="text-right">
            <button class="add-button" onclick="openAddAlatUkurModal()">Tambah Alat Ukur</button>
        </div>
        
        <div class="dropdown-container">
            <!-- Dropdown RO -->
            <select id="roFilter" onchange="filterTable()">
                <option value="">Pilih RO</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->nama_region }}">{{ $region->nama_region }}</option>
                @endforeach
            </select>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" class="custom-select" placeholder="Cari" onkeyup="searchTable()" />
            </div>
        </div>

        <!-- Table Data -->
        <div class="table-container">
            <table id="alatukurTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>RO</th>
                        <th>Kode</th>
                        <th>Nama Alat</th>
                        <th>Merk</th>
                        <th>Type</th>
                        <th>Serial Number</th>
                        <th>Tahun Perolehan</th>
                        <th>Kondisi Alat</th>
                        <th>Harga Pembelian</th>
                        <th>No Kontrak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alat_ukur as $alat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="ro">{{ $alat->RO }}</td>
                        <td>{{ $alat->kode }}</td>
                        <td>{{ $alat->nama_alat }}</td>
                        <td>{{ $alat->merk }}</td>
                        <td>{{ $alat->type }}</td>
                        <td>{{ $alat->serial_number }}</td>
                        <td>{{ $alat->tahun_perolehan }}</td>
                        <td class="kondisi">{{ $alat->kondisi_alat }}</td>
                        <td>{{ $alat->harga_pembelian }}</td>
                        <td>{{ $alat->no_kontrak_spk }}</td>
                        <td>
                            <button type="button" class="edit-button" onclick="editAlatUkur('{{ $alat->urutan }}')" style="background-color: #4CAF50; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                                Edit
                            </button>
                            <button class="delete-btn" onclick="destroyAlatUkur({{ $alat->urutan }})">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addAlatUkurModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddAlatUkurModal()">×</button>
        <h2>Tambah Alat Ukur Baru</h2>
        <form id="addAlatUkurForm" method="POST" action="{{ route('alatukur.store') }}">
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
                        <label for="kode">Kode</label>
                        <input type="text" id="kode" name="kode" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_alat">Nama Alat</label>
                        <input type="text" id="nama_alat" name="nama_alat" required>
                    </div>

                    <div class="form-group">
                        <label for="merk">Merk</label>
                        <input type="text" id="merk" name="merk" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <input type="text" id="type" name="type" class="auto-fill-input">
                    </div>
                
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="serial_number">Serial Number</label>
                        <input type="text" id="serial_number" name="serial_number" class="auto-fill-input">
                    </div>
                    <div class="form-group">
                        <label for="tahun_perolehan">Tahun Perolehan</label>
                        <input type="text" id="tahun_perolehan" name="tahun_perolehan" class="auto-fill-input">
                    </div>

                    <div class="form-group">
                        <label for="kondisi_alat">Kondisi Alat</label>
                        <select id="kondisi_alat" name="kondisi_alat" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="Normal">Normal</option>
                            <option value="Rusak Total">Rusak Total</option>
                            <option value="Rusak Sedang">Rusak Sedang</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="harga_pembelian">Harga Pembelian</label>
                        <input type="text" id="harga_pembelian" name="harga_pembelian" class="auto-fill-input">
                    </div>

                    <div class="form-group">
                        <label for="no_kontrak_spk">No Kontrak</label>
                        <input type="text" id="no_kontrak_spk" name="no_kontrak_spk" class="auto-fill-input">
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
        const rows = document.querySelectorAll("#alatukurTable tbody tr");

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

    function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll("#alatukurTable tbody tr");

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

    function openAddAlatUkurModal() {
        document.getElementById("addAlatUkurModal").style.display = "flex";
    }

    function closeAddAlatUkurModal() {
        $('#addAlatUkurForm')[0].reset();
        $('#serial_number').val('-');
        $('#type').val('-');
        $('#tahun_perolehan').val('-');
        $('#harga_pembelian').val('-');
        $('#addAlatUkurModal .modal-content h2').text('Tambah Alat Ukur Baru');
        $('#addAlatUkurModal .add-button[type="submit"]').text('Simpan');
        document.getElementById("addAlatUkurModal").style.display = "none";
    }

    // Optional: close modal when clicking outside of the modal content
    window.onclick = function(event) {
        const modal = document.getElementById("addAlatUkurModal");
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

    $('#addAlatUkurForm').submit(function(e) {
        e.preventDefault();
        
        const isEdit = $('#alat-id-input').length > 0;
        const url = isEdit ? `/alatukur/${$('#alat-id-input').val()}` : '/alatukur';
        const method = isEdit ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                    closeAddAlatUkurModal();
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan data',
                    icon: 'error'
                });
            }
        });
    });

    function editAlatUkur(urutan) {
        $.get(`/alatukur/${urutan}`, function(response) {
            if (response.success) {
                const alat = response.alat_ukur;
                
                $('#addAlatUkurForm')[0].reset();
                
                $('#roAdd').val(alat.ro);
                $('#kode').val(alat.kode);
                $('#nama_alat').val(alat.nama_alat);
                $('#merk').val(alat.merk);
                $('#type').val(alat.type);
                $('#serial_number').val(alat.serial_number);
                $('#tahun_perolehan').val(alat.tahun_perolehan);
                $('#kondisi_alat').val(alat.kondisi_alat);
                $('#harga_pembelian').val(alat.harga_pembelian);
                $('#no_kontrak_spk').val(alat.no_kontrak_spk);
                
                $('#alat-id-input').remove();
                $('#addAlatUkurForm').append(`<input type="hidden" id="alat-id-input" name="id" value="${alat.urutan}">`);
                
                $('#addAlatUkurModal .modal-content h2').text('Edit Alat Ukur');
                $('#addAlatUkurModal .add-button[type="submit"]').text('Update');
                
                openAddAlatUkurModal();
            }
        });
    }

    function destroyAlatUkur(urutan) {
    

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/alatukur/delete/${urutan}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Delete response:', response);
                        if (response.success) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Delete error:', xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        // Daftar input yang akan diatur
        const autoFillInputs = [
            'serial_number',
            'type',
            'tahun_perolehan',
            'harga_pembelian'
        ];

        // Tambahkan event listener untuk setiap input
        autoFillInputs.forEach(function(inputId) {
            $(`#${inputId}`).blur(function() {
                if ($(this).val().trim() === '') {
                    $(this).val('-');
                }
            });
        });

        // Reset form saat modal dibuka
        function openAddAlatUkurModal() {
            autoFillInputs.forEach(function(inputId) {
                if ($(`#${inputId}`).val().trim() === '') {
                    $(`#${inputId}`).val('-');
                }
            });
            $('#addAlatUkurModal').show();
        }

        // Reset form saat modal ditutup
        function closeAddAlatUkurModal() {
            autoFillInputs.forEach(function(inputId) {
                $(`#${inputId}`).val('-');
            });
            $('#addAlatUkurModal').hide();
        }

        // Tambahkan event untuk menghapus tanda "-" saat input difokuskan
        autoFillInputs.forEach(function(inputId) {
            $(`#${inputId}`).focus(function() {
                if ($(this).val() === '-') {
                    $(this).val('');
                }
            });
        });

        // Handler form submit
        $('#addAlatUkurForm').submit(function(e) {
            autoFillInputs.forEach(function(inputId) {
                if ($(`#${inputId}`).val().trim() === '') {
                    $(`#${inputId}`).val('-');
                }
            });
        });
    });
</script>
@endsection

