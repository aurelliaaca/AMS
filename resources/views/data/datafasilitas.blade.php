@extends('layouts.sidebar')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/data.css') }}">
<link rel="stylesheet" href="{{ asset('css/general.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabeldata.css') }}">

<div class="main">
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center;">
                    <button class="back-btn" onclick="window.location.href='/data'">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin-left: 10px;">Data Fasilitas</h3>
                </div>
            </div>
        </div>
        <div class="titles-container">
        <div class="section">
                <div class="section-title">
                    <div class="title-wrapper">
                        <span><i class="fas fa-tags" style="font-size: 18px;"></i></span>
                        <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin-left: 5px;">Jenis</h3>
                    </div>
                    @if(auth()->user()->role == '1')
                    <button class="add-button-data" onclick="openAddJenisModal()">
                        <span><i class="fas fa-plus"></i></span>
                    </button>
                    @endif
                </div>
                <div class="table-container">
                    <div class="table-wrapper">
                        <table id="jenisFasilitasTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Kode Fasilitas</th>
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
                        <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin-left: 5px;">Brand</h3>
                        </div>
                        @if(auth()->user()->role == '1')
                    <button class="add-button-data" onclick="openAddBrandModal()">
                        <span><i class="fas fa-plus"></i></span>
                    </button>
                    @endif
                </div>
                <div class="table-container">
                    <div class="table-wrapper">
                        <table id="brandFasilitasTable">
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

@include('data.add-datafasilitas')
@include('data.edit-datafasilitas')

<script>
    $(document).ready(function() {
        loadData();
    });

    function loadData() {
        $.ajax({
            url: '/data/get-fasilitas',
            method: 'GET',
            success: function(data) {
                // Bersihkan tabel terlebih dahulu
                $('#brandFasilitasTable tbody').empty();
                $('#jenisFasilitasTable tbody').empty();

                // Menampilkan data brand fasilitas
                $.each(data.brandFasilitas, function(index, brand) {
                    $('#brandFasilitasTable tbody').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${brand.nama_brand}</td>
                            <td>${brand.kode_brand}</td>
                            <td>
                                                @if(auth()->user()->role == '1')
                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="editBrand('${brand.kode_brand}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-btn" onclick="deleteBrand('${brand.kode_brand}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                    `);
                });

                // Menampilkan data jenis fasilitas
                $.each(data.jenisFasilitas, function(index, jenis) {
                    $('#jenisFasilitasTable tbody').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${jenis.nama_fasilitas}</td>
                            <td>${jenis.kode_fasilitas}</td>
                            <td>
                                                @if(auth()->user()->role == '1')

                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="editJenis('${jenis.kode_fasilitas}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-btn" onclick="deleteJenis('${jenis.kode_fasilitas}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
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

    function deleteJenis(kodeFasilitas) {
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
                    url: `/data/jenis-fasilitas/${kodeFasilitas}`,
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
                    url: `/data/brand-fasilitas/${kodeBrand}`,
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