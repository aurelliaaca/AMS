@extends('layouts.sidebar')
@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Perangkat</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/data.css') }}">
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
</head>

<div class="main">
    <div class="container">
        <div class="titles-container">
        <div class="section">
                <div class="section-title">
                    <div class="title-wrapper">
                        <span><i class="fas fa-tags" style="font-size: 18px;"></i></span>
                        <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin-left: 5px;">Jenis Perangkat</h3>
                    </div>
                    <button class="add-button-data" onclick="openAddJenisModal()">
                        <span><i class="fas fa-plus"></i></span>
                    </button>
                </div>
                <div class="table-container">
                    <div class="table-wrapper">
                        <table id="jenisPerangkatTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Perangkat</th>
                                    <th>Kode Perangkat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">
                    <div class="title-wrapper">
                        <span><i class="fas fa-building" style="font-size: 18px;"></i></span>
                        <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin-left: 5px;">Brand Perangkat</h3>
                        </div>
                    <button class="add-button-data" onclick="openAddBrandModal()">
                        <span><i class="fas fa-plus"></i></span>
                    </button>
                </div>
                <div class="table-container">
                    <div class="table-wrapper">
                        <table id="brandPerangkatTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Brand</th>
                                    <th>Kode Brand</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan ditambahkan di sini oleh AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('data.add-dataperangkat')
@include('data.edit-dataperangkat')

<script>
    $(document).ready(function() {
        loadData();
    });

    function loadData() {
        $.ajax({
            url: '/data/get-perangkat',
            method: 'GET',
            success: function(data) {
                // Bersihkan tabel terlebih dahulu
                $('#brandPerangkatTable tbody').empty();
                $('#jenisPerangkatTable tbody').empty();

                // Menampilkan data brand perangkat
                $.each(data.brandPerangkat, function(index, brand) {
                    $('#brandPerangkatTable tbody').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${brand.nama_brand}</td>
                            <td>${brand.kode_brand}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="editBrand('${brand.kode_brand}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-btn" onclick="deleteBrand('${brand.kode_brand}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);
                });

                // Menampilkan data jenis perangkat
                $.each(data.jenisPerangkat, function(index, jenis) {
                    $('#jenisPerangkatTable tbody').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${jenis.nama_perangkat}</td>
                            <td>${jenis.kode_perangkat}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="editJenis('${jenis.kode_perangkat}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-btn" onclick="deleteJenis('${jenis.kode_perangkat}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function() {
                Swal.fire('Error!', 'Terjadi kesalahan saat mengambil data.', 'error');
            }
        });
    }

    function openAddBrandModal() {
        document.getElementById("addBrandModal").style.display = "flex";
    }

    function closeAddBrandModal() {
        document.getElementById("addBrandModal").style.display = "none";
    }

    function deleteJenis(kodePerangkat) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/data/jenis-perangkat/${kodePerangkat}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            ).then(() => {
                                loadData();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error Details:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        Swal.fire('Error!', `Terjadi kesalahan saat menghapus data. Status: ${status}`, 'error');
                    }
                });
            }
        });
    }
    
    function deleteBrand(kodeBrand) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/data/brand-perangkat/${kodeBrand}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            ).then(() => {
                                loadData();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error Details:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        Swal.fire('Error!', `Terjadi kesalahan saat menghapus data. Status: ${status}`, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection