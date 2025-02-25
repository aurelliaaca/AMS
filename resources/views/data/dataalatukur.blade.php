@extends('layouts.sidebar')

@section('content')
<div class="main">
    <div class="container">
                <h3>Informasi Alat Ukur</h3>


        <!-- Titles Container -->
        <div class="titles-container">
            <!-- Title AlatUkur -->
            <div class="section-title">
                <div class="title-wrapper">
                    <span class="material-symbols-outlined">devices</span>
                    <h4>Nama Alat Ukur</h4>
                </div>
                <button type="button" class="add-button" onclick="openJenisAlatUkurModal()" title="Tambah Alat Ukur">
                    <span class="material-symbols-outlined">add</span>
                </button>
            </div>

            <!-- Title Brand -->
            <div class="section-title">
                <div class="title-wrapper">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <h4>Brand Alat Ukur</h4>
                </div>
                <button type="button" class="add-button" onclick="openBrandAlatUkurModal()" title="Tambah Brand">
                    <span class="material-symbols-outlined">add</span>
                </button>
            </div>
        </div>

        <!-- Tables Container -->
        <div class="table-container">
            <!-- Tabel Nama AlatUkur -->
            <div class="table-section">
                <div class="table-wrapper">
                    <table id="jenisAlatUkurTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Alat Ukur</th>
                                <th>Kode Alat Ukur</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jenisalatukur as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_alatukur }}</td>
                                    <td>{{ $item->kode_alatukur }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit-btn" title="Edit" onclick="editInfoAlatUkur({{ $item->id }})">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>
                                            <button class="delete-btn" title="Hapus" onclick="deleteInfoAlatUkur({{ $item->id }})">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

    



<!--============================================ Tabel Brand AlatUkur ============================================-->
            <div class="table-section">
                <div class="table-wrapper">
                    <table id="brandAlatUkurTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Brand</th>
                                <th>Kode Brand</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($brandalatukur as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_brand }}</td>
                                    <td>{{ $item->kode_brand }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit-btn" title="Edit" onclick="editBrandAlatUkur({{ $item->id }})">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>
                                            <button class="delete-btn" title="Hapus" onclick="deleteBrandAlatUkur({{ $item->id }})">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

<style>
    :root {
        --primary-color: #4f52ba;
        --primary-light: #6366F1;
        --primary-dark: #3a3d9c;
        --secondary-color: #2DD4BF;
        --danger-color: #dc2626;
        --danger-light: #ef4444;
    }

    .container {
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 30px;
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .dashboard-header h1 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #4f52ba;
    }

    .dashboard-header p {
        color: #7f8c8d;
        font-size: 14px;
    }

    /* Titles Container */
    .titles-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
        gap: 30px;
        margin-bottom: 20px;
    }

    .section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .title-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .title-wrapper span {
        color: var(--primary-color);
        font-size: 24px;
    }

    .title-wrapper h4 {
        color: #2c3e50;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .add-button {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-color);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .add-button:hover {
        background: var(--primary-light);
        transform: rotate(90deg);
    }

    .add-button span {
        color: white;
        font-size: 20px;
    }

    /* Tables Container */
    .table-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
        gap: 30px;
        margin: 20px 0;
    }

    .table-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .table-wrapper {
        padding: 20px;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    table th:nth-child(1), 
    table td:nth-child(1) {
        width: 10%;
    }

    table th:nth-child(2), 
    table td:nth-child(2) {
        width: 35%;
    }

    table th:nth-child(3), 
    table td:nth-child(3) {
        width: 35%;
    }

    table th:nth-child(4), 
    table td:nth-child(4) {
        width: 20%;
    }

    thead th {
        background: #4f52ba;
        color: white;
        font-size: 14px;
        font-weight: 600;
        padding: 15px;
        text-align: left;
        white-space: nowrap;
    }

    tbody td {
        padding: 15px;
        color: #4a5568;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        word-wrap: break-word;
    }

    tbody tr:hover {
        background: #f8fafc;
        transition: background-color 0.3s ease;
    }

    /* Tombol Aksi (Edit & Delete) */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: flex-start;
        min-width: 90px;
    }

    .edit-btn, .delete-btn {
        background: none;
        border: none;
        padding: 4px;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .edit-btn span {
        color: #4f52ba;
        font-size: 20px;
    }

    .delete-btn span {
        color: #dc2626;
        font-size: 20px;
    }

    .edit-btn:hover {
        background: #eff6ff;
    }

    .delete-btn:hover {
        background: #fef2f2;
    }

    /* Responsivitas */
    @media (max-width: 1200px) {
        .table-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .table-wrapper {
            padding: 10px;
        }
        
        thead th, tbody td {
            padding: 10px;
        }
    }
</style>

<!-- Link untuk Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<!-- Modal untuk Nama Alat Ukur -->
<div id="addJenisAlatUkurModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <span class="modal-close-btn" onclick="closeJenisAlatUkurModal()">&times;</span>
        <h2>Tambah Nama AlatUkur</h2>
        <form id="jenisAlatUkurForm">
            @csrf
            <div class="form-group">
                <label for="alatukur">Nama AlatUkur</label>
                <input type="text" id="alatukur" name="alatukur" required>
            </div>
            <div class="form-group">
                <label for="kode_alatukur">Kode AlatUkur</label>
                <input type="text" id="kode_alatukur" name="kode_alatukur" required>
            </div>
            <button type="submit" class="submit-btn">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal untuk Brand AlatUkur -->
<div id="addBrandAlatUkurModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <span class="modal-close-btn" onclick="closeBrandAlatUkurModal()">&times;</span>
        <h2>Tambah Brand AlatUkur</h2>
        <form id="brandAlatUkurForm">
            @csrf
            <div class="form-group">
                <label for="nama_brand">Nama Brand</label>
                <input type="text" id="nama_brand" name="nama_brand" required>
            </div>
            <div class="form-group">
                <label for="kode_brand">Kode Brand</label>
                <input type="text" id="kode_brand" name="kode_brand" required>
            </div>
            <button type="submit" class="submit-btn">Simpan</button>
        </form>
    </div>
</div>

@push('scripts')
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Load SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<!-- Load Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Your custom scripts -->
<script>
$(document).ready(function() {
    // Debug
    console.log('JavaScript loaded');

    // Setup AJAX CSRF
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



    
    // ============== NAMA PERANGKAT OPERATIONS ==============
    // Open modal Nama AlatUkur
    function openJenisAlatUkurModal() {
        console.log('Opening Nama AlatUkur Modal');
        $('#addJenisAlatUkurModal').show();
    }

    function closeJenisAlatUkurModal() {
        $('#addJenisAlatUkurModal').hide();
    }

    // Fungsi Modal Brand AlatUkur
    function openBrandAlatUkurModal() {
        console.log('Opening Brand AlatUkur Modal');
        $('#addBrandAlatUkurModal').show();
    }

    function closeBrandAlatUkurModal() {
        $('#addBrandAlatUkurModal').hide();
    }

    // Form Submit Nama AlatUkur
    $('#jenisAlatUkurForm').submit(function(e) {
        e.preventDefault();
        console.log('Submitting Nama AlatUkur Form');
        
        $.ajax({
            url: '/store-jenisalatukur',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    swal("Berhasil!", "Data berhasil disimpan", "success");
                    closeJenisAlatUkurModal();
                    location.reload();
                }
            },
            error: function(xhr) {
                swal("Error!", "Terjadi kesalahan", "error");
                console.error(xhr.responseText);
            }
        });
    });

    // Form Submit Brand AlatUkur
    $('#brandAlatUkurForm').submit(function(e) {
        e.preventDefault();
        console.log('Submitting Brand AlatUkur Form');
        
        $.ajax({
            url: '/store-brandalatukur',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    swal("Berhasil!", "Data berhasil disimpan", "success");
                    closeBrandAlatUkurModal();
                    location.reload();
                }
            },
            error: function(xhr) {
                swal("Error!", "Terjadi kesalahan", "error");
                console.error(xhr.responseText);
            }
        });
    });

        // Edit Nama AlatUkur
        function editInfoAlatUkur(id) {
            console.log('Editing Nama AlatUkur:', id);
            $.get(`/get-jenisalatukur/${id}`, function(response) {
                if (response.success) {
                    $('#alatukur').val(response.data.alatukur);
                    $('#kode_alatukur').val(response.data.kode_alatukur);
                    $('#jenisAlatUkurForm').append(`<input type="hidden" name="id" value="${id}">`);
                    openJenisAlatUkurModal();
                }
            });
        }

    // Delete Nama AlatUkur
    function deleteInfoAlatUkur(id) {
        console.log('Deleting Nama AlatUkur:', id);
        swal({
            title: "Apakah Anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, hapus!",
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: `/delete-jenisalatukur/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            swal("Berhasil!", "Data berhasil dihapus", "success");
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        swal("Error!", "Terjadi kesalahan", "error");
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    }

    // Edit Brand AlatUkur
    function editBrandAlatUkur(id) {
        console.log('Editing Brand AlatUkur:', id);
        $.get(`/get-brandalatukur/${id}`, function(response) {
            if (response.success) {
                $('#nama_brand').val(response.data.nama_brand);
                $('#kode_brand').val(response.data.kode_brand);
                $('#brandAlatUkurForm').append(`<input type="hidden" name="id" value="${id}">`);
                openBrandAlatUkurModal();
            }
        });
    }

    // Delete Brand AlatUkur
    function deleteBrandAlatUkur(id) {
        console.log('Deleting Brand AlatUkur:', id);
        swal({
            title: "Apakah Anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, hapus!",
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: `/delete-brandalatukur/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            swal("Berhasil!", "Data berhasil dihapus", "success");
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        swal("Error!", "Terjadi kesalahan", "error");
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    }
});
</script>
@endpush

<!-- Pastikan jQuery dan SweetAlert sudah di-load -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection