@extends('layouts.sidebar')

@section('content')
<head>
    <title>Data Fasilitas</title>
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
                        <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin-left: 5px;">Jenis Fasilitas</h3>
                    </div>
                    <button class="add-button-data" onclick="openAddJenisModal()">
                        <span><i class="fas fa-plus"></i></span>
                    </button>
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
                                <!-- Data akan ditambahkan di sini oleh AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">
                    <div class="title-wrapper">
                        <span><i class="fas fa-building" style="font-size: 18px;"></i></span>
                        <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin-left: 5px;">Brand Fasilitas</h3>
                        </div>
                    <button class="add-button-data" onclick="openAddBrandModal()">
                        <span><i class="fas fa-plus"></i></span>
                    </button>
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

@include('data.add-brandfasilitas')

<script>
    $(document).ready(function() {
        loadData();
    });

    function loadData() {
        $.ajax({
            url: '/data/get',
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
                                <div class="action-buttons">
                                    <button class="edit-brand-btn" onclick="openEditBrandModal(${brand.kode_brand})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-btn" onclick="deleteBrand(${brand.kode_brand})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
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
                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="editJenis(${jenis.kode_fasilitas})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-btn" onclick="deleteJenis(${jenis.kode_fasilitas})">
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
</script>
@endsection