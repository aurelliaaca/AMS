@extends('layouts.sidebar')

@section('content')
    <style>
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
        .tables-wrapper {
            display: flex;
            gap: 20px;
        }

        .table-container h3 {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th:first-child {
            border-top-left-radius: 8px;
        }
        
        th:last-child {
            border-top-right-radius: 8px;
        }
        
        tr:last-child td:first-child {
            border-bottom-left-radius: 8px;
        }
        
        tr:last-child td:last-child {
            border-bottom-right-radius: 8px;
        }
        
        th {
            background-color: #4f52ba;
            color: #fff;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-weight: normal;
            font-size: 12px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .no-data {
            text-align: center;
            color: rgba(79, 82, 186, 0.2);
        }

        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin: 0 2px;
        }

        .btn-edit {
            background-color: #4f52ba;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-edit:hover {
            background-color: #3a3d9c;
        }

        .btn-delete:hover {
            background-color: #c82333;
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
        }

        .add-button:hover {
            background-color: #3a3d9c;
        }

        /* Modal Styles */
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

        .modal-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 20px;
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
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Region</h3>
                    <button type="button" class="add-button" onclick="openAddRegionModal()">Tambah Region</button>
                </div>
            </div>

            <!-- Table Data Region -->
            <div class="table-container">
                <table id="tableRegion">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Region</th>
                            <th>Kode Region</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($regions as $index => $region)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $region->nama_region }}</td>
                                <td>{{ $region->kode_region }}</td>
                                <td>{{ $region->email }}</td>
                                <td>
                                    <button type="button" class="btn-edit" onclick="editRegion({{ $region->id_region }})">Edit</button>
                                    <button type="button" class="btn-delete" onclick="deleteRegion({{ $region->id_region }})">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="no-data">Tidak ada data region</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="addRegionModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <button class="modal-close-btn" onclick="closeAddRegionModal()">×</button>
            <h2>Tambah Region Baru</h2>
            <form id="addRegionForm">
                @csrf
                <div class="form-group">
                    <label for="nama_region">Nama Region</label>
                    <input type="text" id="nama_region" name="nama_region" required>
                </div>
                <div class="form-group">
                    <label for="kode_region">Kode Region</label>
                    <input type="text" id="kode_region" name="kode_region" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat">
                </div>
                <div class="form-group">
                    <label for="koordinat">Koordinat</label>
                    <input type="text" id="koordinat" name="koordinat" placeholder="contoh: -6.123456, 106.123456">
                </div>
                <div class="button-container">
                    <button type="submit" class="add-button">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
       function openAddRegionModal() {
            document.getElementById("addRegionModal").style.display = "flex";
        }

        function closeAddRegionModal() {
            document.getElementById("addRegionModal").style.display = "none";
        }

        // Optional: close modal when clicking outside of the modal content
        window.onclick = function(event) {
            const modal = document.getElementById("addRegionModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };

    function addRegionSuccess() {
        showTemporaryMessage();
    }

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
        } else if (type === 'confirm-delete') {
            swal({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#4f52ba",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                closeOnConfirm: false
            }, function(isConfirm) {
                if (isConfirm) {
                    // Lanjutkan dengan penghapusan
                    return true;
                }
            });
        }
    }

    // Tambahkan fungsi untuk menutup modal
    function closeAddRegionModal() {
        $('#addRegionModal').hide();
        $('#addRegionForm')[0].reset();
    }

    // Perbaikan handler form submission
    $('#addRegionForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = $('#addRegionForm').data('edit');
        const id_region = $('#addRegionForm').data('id_region');
        
        let url = '/store-region';
        let method = 'POST';
        
        if (isEdit) {
            url = `/update-region/${id_region}`;
            formData.append('_method', 'PUT'); // Tambahkan method spoofing untuk PUT
        }
        
        $.ajax({
            url: url,
            type: 'POST', // Selalu gunakan POST, dengan _method untuk PUT
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    closeAddRegionModal(); // Tutup modal sebelum menampilkan pesan sukses
                    swal({
                        title: "Berhasil!",
                        text: response.message,
                        type: "success",
                        button: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "btn btn-primary"
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = "Terjadi kesalahan saat menyimpan data";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                swal({
                    title: "Error!",
                    text: errorMessage,
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
    });

    // Tambahkan event listener untuk menutup modal saat mengklik di luar modal
    $(window).click(function(event) {
        const modal = $('#addRegionModal');
        if (event.target === modal[0]) {
            closeAddRegionModal();
        }
    });

    // Fungsi untuk membuka modal region
    function openAddRegionModal() {
        $('#addRegionModal').show();
        // Reset form ketika membuka modal
        $('#addRegionForm')[0].reset();
        // Reset judul dan tombol ke mode tambah
        $('h2').text('Tambah Region Baru');
        $('.add-button[type="submit"]').text('Simpan');
    }

    // Perbaikan fungsi editRegion
    function editRegion(id_region) {
        $.get(`/get-region/${id_region}`, function(response) {
            if (response.success) {
                const region = response.region;
                
                // Isi form dengan data yang ada
                $('#nama_region').val(region.nama_region);
                $('#kode_region').val(region.kode_region);
                $('#email').val(region.email);
                $('#alamat').val(region.alamat);
                $('#koordinat').val(region.koordinat);
                
                // Set form ke mode edit
                $('#addRegionForm').data('edit', true);
                $('#addRegionForm').data('id_region', id_region);
                
                // Ubah judul modal dan text tombol
                $('h2').text('Edit Region');
                $('.add-button[type="submit"]').text('Update');
                
                // Tampilkan modal
                openAddRegionModal();
            }
        });
    }

    // Modifikasi fungsi delete Region
    function deleteRegion(id_region) {
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
                    url: `/delete-region/${id_region}`,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: "Terhapus!",
                                text: "Data berhasil dihapus.",
                                type: "success",
                                button: {
                                    text: "OK",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-primary"
                                }
                            });
                            loadRegionData();
                        } else {
                            swal({
                                title: "Error!",
                                text: response.message || "Gagal menghapus data",
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
                            text: "Terjadi kesalahan saat menghapus data",
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

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">


