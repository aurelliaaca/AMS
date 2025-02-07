@extends('layouts.sidebar')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    
   
    
    <!-- Meta tag CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            table-layout: auto;
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
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input:focus {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.3);
            outline: none;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        .add-button {
            background-color: #4f52ba;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-button:hover {
            background-color: #3e44a0;
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
    </style>

<div class="main">
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Fasilitas</h3>
                <button class="add-button" onclick="openAddFasilitasModal()">Tambah Fasilitas</button>
            </div>
        </div>
        <div class="dropdown-container" style="margin-top: 20px;">
            <select id="ROFilter" class="filter-select">
                <option value="">Pilih RO</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->nama_region }}">{{ $region->nama_region }}</option>
                @endforeach
            </select>

            <select id="POPFilter" class="filter-select">
                <option value="">Pilih Nama POP</option>
                @foreach ($fasilitas as $data)
                <option value="{{ $data->nama_POP }}">{{ $data->nama_POP }}</option>
                @endforeach
            </select>
            <select name="" id="PERANGKATfilter" class="filter-select">
                <option value="">Pilih Nama Perangkat</option>
                @foreach($fasilitas as $data)
                <option value="{{$data->perangkat}}">{{$data->perangkat}}</option>
                @endforeach
            </select>
            <select name="" id="MERKfilter" class="filter-select">
                <option value="">Pilih Nama Merk</option>
                @foreach($fasilitas as $data)
                <option value="{{$data->merk}}">{{$data->merk}}</option>
                @endforeach
            </select>


            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTable()" />
            </div>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>RO</th>
                        <th>Nama POP</th>
                        <th>Perangkat</th>
                        <th>Merk</th>
                        <th>Tipe</th>
                        <th>Serial Number</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Update Site Visit</th>
                        <th>Tanggal Site Visit</th>
                        <th>Hostname</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataRows">
                    @forelse ($fasilitas as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->RO }}</td>
                            <td>{{ $data->nama_POP }}</td>
                            <td>{{ $data->perangkat }}</td>
                            <td>{{ $data->merk }}</td>
                            <td>{{ $data->tipe }}</td>
                            <td>{{ $data->serial_Number }}</td>
                            <td>{{ $data->jumlah }}</td>
                            <td>{{ $data->satuan }}</td>
                            <td class="status {{ $data->status }}">{{ $data->status }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>{{ $data->update_site_visit }}</td>
                            <td>{{ $data->tanggal_site_visit }}</td>
                            <td>{{ $data->hostname }}</td>
                            <td>
                                <button class="edit-btn" onclick="editFasilitas('{{ $data->urutan }}')">Edit</button>
                                <button class="delete-btn" onclick="deleteFasilitas('{{ $data->urutan }}')">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addFasilitasModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddFasilitasModal()">×</button>
        <h2>Tambah Fasilitas Baru</h2>
        <form id="addFasilitasForm" method="POST">
            @csrf
            <div class="form-group">
                <label for="RO">RO</label>
                <input type="text" id="RO" name="RO" required>
            </div>
            <div class="form-group">
                <label for="nama_POP">Nama POP</label>
                <input type="text" id="nama_POP" name="nama_POP" required>
            </div>
            <div class="form-group">
                <label for="perangkat">Perangkat</label>
                <input type="text" id="perangkat" name="perangkat" required>
            </div>
            <div class="form-group">
                <label for="merk">Merk</label>
                <input type="text" id="merk" name="merk" required>
            </div>
            <div class="form-group">
                <label for="tipe">Tipe</label>
                <input type="text" id="tipe" name="tipe" required>
            </div>
            <div class="form-group">
                <label for="serial_Number">Serial Number</label>
                <input type="text" id="serial_Number" name="serial_Number" required>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" id="jumlah" name="jumlah" required>
            </div>
            <div class="form-group">
                <label for="satuan">Satuan</label>
                <input type="text" id="satuan" name="satuan" required>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button">Simpan</button>
            </div>
        </form>
    </div>
</div>


    <script>
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('.table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        if (cells[j].innerText.toLowerCase().includes(filter)) {
                            found = true;
                        }
                    }
                }

                rows[i].style.display = found ? '' : 'none';
            }
        }
        
        $(document).ready(function() {
            const rows = $('#dataRows tr');

            $('#ROFilter').select2({ placeholder: "Pilih RO", allowClear: true });
            $('#POPFilter').select2({ placeholder: "Pilih POP", allowClear: true }); 
            $('#PERANGKATfilter').select2({ placeholder: "Pilih Perangkat", allowClear: true });
            $('#MERKfilter').select2({ placeholder: "Pilih Merk", allowClear: true });

            $('#ROFilter').change(function() {
                const selectedRO = $(this).val();
                rows.hide();

                if (selectedRO) {
                    rows.each(function() {
                        const roCell = $(this).find('td').eq(1).text();
                        if (roCell === selectedRO) {
                            $(this).show();
                        }
                    });
                } else {
                    rows.show();
                }
            });

            $('#POPFilter, #PERANGKATfilter, #MERKfilter').change(function() {
                const selectedRO = $('#ROFilter').val();
                const selectedPOP = $('#POPFilter').val();
                const selectedPerangkat = $('#PERANGKATfilter').val();
                const selectedMerk = $('#MERKfilter').val();

                rows.each(function() {
                    const roCell = $(this).find('td').eq(1).text(); 
                    const popCell = $(this).find('td').eq(2).text(); 
                    const perangkatCell = $(this).find('td').eq(3).text(); 
                    const merkCell = $(this).find('td').eq(4).text(); 

                    if ((selectedRO === '' || roCell === selectedRO) &&
                        (selectedPOP === '' || popCell === selectedPOP) &&
                        (selectedPerangkat === '' || perangkatCell === selectedPerangkat) &&
                        (selectedMerk === '' || merkCell === selectedMerk)) {
                        $(this).show(); 
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#addFasilitasForm').submit(function(e) {
                e.preventDefault();
                const wdm = $('#wdm-input').val();
                const url = wdm ? `/update-fasilitas/${wdm}` : '/store-fasilitas';
                const method = wdm ? 'PUT' : 'POST';
                
                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            closeAddFasilitasModal();
                            showSwal('success', wdm ? 'Fasilitas berhasil diupdate!' : 'Fasilitas berhasil ditambahkan!');
                            LoadData();
                            $('#addFasilitasForm')[0].reset();
                            $('#wdm-input').remove();
                        } else {
                            showSwal('error', response.message || 'Terjadi kesalahan');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        showSwal('error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });
        });

        function openAddFasilitasModal() {
            $('#addFasilitasModal').show();
        }

        function closeAddFasilitasModal() {
            $('#addFasilitasModal').hide();
        }

        function editFasilitas(urutan) {
            $.get(`/get-fasilitas/${urutan}`, function(response) {
                if (response.success) {
                    const fasilitas = response.fasilitas;
                    
                    $('#addFasilitasForm')[0].reset();
                    
                    $('#RO').val(fasilitas.RO);
                    $('#nama_POP').val(fasilitas.nama_POP);
                    $('#perangkat').val(fasilitas.perangkat);
                    $('#merk').val(fasilitas.merk);
                    $('#tipe').val(fasilitas.tipe);
                    $('#serial_Number').val(fasilitas.serial_Number);
                    $('#jumlah').val(fasilitas.jumlah);
                    $('#satuan').val(fasilitas.satuan);
                    
                    $('#addFasilitasForm').append(`<input type="hidden" id="wdm-input" name="urutan" value="${fasilitas.urutan}">`);
                    
                    $('h2').text('Edit Fasilitas');
                    $('.add-button[type="submit"]').text('Update');
                    
                    openAddFasilitasModal();
                }
            });
        }

        function deleteFasilitas(urutan) {
            swal({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                closeOnConfirm: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: `/delete-fasilitas/${urutan}`,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                swal({
                                    title: "Terhapus!",
                                    text: "Fasilitas berhasil dihapus.",
                                    type: "success",
                                    button: {
                                        text: "OK",
                                        value: true,
                                        visible: true,
                                        className: "btn btn-primary"
                                    }
                                });
                                LoadData();
                            } else {
                                swal({
                                    title: "Error!",
                                    text: response.message || "Gagal menghapus fasilitas",
                                    type: "error",
                                    button: {
                                        text: "OK",
                                        value: true,
                                        visible: true,
                                        className: "btn btn-danger"
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Delete error details:', {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });
                            swal({
                                title: "Error!",
                                text: "Terjadi kesalahan saat menghapus fasilitas",
                                type: "error",
                                button: {
                                    text: "OK",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-danger"
                                }
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection