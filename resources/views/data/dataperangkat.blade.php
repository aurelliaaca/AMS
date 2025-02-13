@extends('layouts.sidebar')

@section('content')
<div class="main">
    <div class="container">
                <h3>Informasi Perangkat</h3>


        <!-- Titles Container -->
        <div class="titles-container">
            <!-- Title Perangkat -->
            <div class="section-title">
                <div class="title-wrapper">
                    <span class="material-symbols-outlined">devices</span>
                    <h4>Nama Perangkat</h4>
                </div>
                <button type="button" class="add-button" onclick="openNamaPerangkatModal()" title="Tambah Perangkat">
                    <span class="material-symbols-outlined">add</span>
                </button>
            </div>

            <!-- Title Brand -->
            <div class="section-title">
                <div class="title-wrapper">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <h4>Brand Perangkat</h4>
                </div>
                <button type="button" class="add-button" onclick="openBrandPerangkatModal()" title="Tambah Brand">
                    <span class="material-symbols-outlined">add</span>
                </button>
            </div>
        </div>

        <!-- Tables Container -->
        <div class="table-container">
            <!-- Tabel Nama Perangkat -->
            <div class="table-section">
                <div class="table-wrapper">
                    <table id="namaPerangkatTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Perangkat</th>
                                <th>Kode Perangkat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($namaperangkat as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->perangkat }}</td>
                                    <td>{{ $item->kode_perangkat }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit-btn" title="Edit" onclick="editInfoPerangkat({{ $item->id }})">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>
                                            <button class="delete-btn" title="Hapus" onclick="deleteInfoPerangkat({{ $item->id }})">
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

    



<!--============================================ Tabel Brand Perangkat ============================================-->
            <div class="table-section">
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
                        @forelse($brandperangkat as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_brand }}</td>
                                    <td>{{ $item->kode_brand }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit-btn" title="Edit" onclick="editInfoPerangkat({{ $item->id }})">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>
                                            <button class="delete-btn" title="Hapus" onclick="deleteInfoPerangkat({{ $item->id }})">
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

<!-- Modal untuk Nama Perangkat -->
<div id="addNamaPerangkatModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <span class="modal-close-btn" onclick="closeNamaPerangkatModal()">&times;</span>
        <h2>Tambah Nama Perangkat</h2>
        <form id="namaPerangkatForm">
            @csrf
            <div class="form-group">
                <label for="perangkat">Nama Perangkat</label>
                <input type="text" id="perangkat" name="perangkat" required>
            </div>
            <div class="form-group">
                <label for="kode_perangkat">Kode Perangkat</label>
                <input type="text" id="kode_perangkat" name="kode_perangkat" required>
            </div>
            <button type="submit" class="add-button">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal untuk Brand Perangkat -->
<div id="addBrandPerangkatModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <span class="modal-close-btn" onclick="closeBrandPerangkatModal()">&times;</span>
        <h2>Tambah Brand Perangkat</h2>
        <form id="brandPerangkatForm">
            @csrf
            <div class="form-group">
                <label for="nama_brand">Nama Brand</label>
                <input type="text" id="nama_brand" name="nama_brand" required>
            </div>
            <div class="form-group">
                <label for="kode_brand">Kode Brand</label>
                <input type="text" id="kode_brand" name="kode_brand" required>
            </div>
            <button type="submit" class="add-button">Simpan</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Setup AJAX CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ============== NAMA PERANGKAT OPERATIONS ==============
    // Open modal Nama Perangkat
    window.openNamaPerangkatModal = function() {
        $('#addNamaPerangkatModal').show();
        $('#namaPerangkatForm')[0].reset();
        $('#id-input-perangkat').remove();
    }

    // Close modal Nama Perangkat
    window.closeNamaPerangkatModal = function() {
        $('#addNamaPerangkatModal').hide();
    }

    // Submit form Nama Perangkat
    $('#namaPerangkatForm').submit(function(e) {
        e.preventDefault();
        const id = $('#id-input-perangkat').val();
        const url = id ? `/update-namaperangkat/${id}` : '/store-namaperangkat';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    closeNamaPerangkatModal();
                    showSwal('success', id ? 'Perangkat berhasil diupdate!' : 'Perangkat berhasil ditambahkan!');
                    window.location.reload();
                }
            },
            error: function(xhr) {
                showSwal('error', 'Terjadi kesalahan');
            }
        });
    });

    // Edit Nama Perangkat
    window.editNamaPerangkat = function(id) {
        $.get(`/get-namaperangkat/${id}`, function(response) {
            if (response.success) {
                const data = response.data;
                $('#perangkat').val(data.perangkat);
                $('#kode_perangkat').val(data.kode_perangkat);
                $('#namaPerangkatForm').append(`<input type="hidden" id="id-input-perangkat" value="${data.id}">`);
                openNamaPerangkatModal();
            }
        });
    }

    // Delete Nama Perangkat
    window.deleteNamaPerangkat = function(id) {
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
                    url: `/delete-namaperangkat/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            swal("Terhapus!", "Perangkat berhasil dihapus.", "success");
                            window.location.reload();
                        }
                    }
                });
            }
        });
    }

    // ============== BRAND PERANGKAT OPERATIONS ==============
    // Open modal Brand Perangkat
    window.openBrandPerangkatModal = function() {
        $('#addBrandPerangkatModal').show();
        $('#brandPerangkatForm')[0].reset();
        $('#id-input-brand').remove();
    }

    // Close modal Brand Perangkat
    window.closeBrandPerangkatModal = function() {
        $('#addBrandPerangkatModal').hide();
    }

    // Submit form Brand Perangkat
    $('#brandPerangkatForm').submit(function(e) {
        e.preventDefault();
        const id = $('#id-input-brand').val();
        const url = id ? `/update-brandperangkat/${id}` : '/store-brandperangkat';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    closeBrandPerangkatModal();
                    showSwal('success', id ? 'Brand berhasil diupdate!' : 'Brand berhasil ditambahkan!');
                    window.location.reload();
                }
            },
            error: function(xhr) {
                showSwal('error', 'Terjadi kesalahan');
            }
        });
    });

    // Edit Brand Perangkat
    window.editBrandPerangkat = function(id) {
        $.get(`/get-brandperangkat/${id}`, function(response) {
            if (response.success) {
                const data = response.data;
                $('#nama_brand').val(data.nama_brand);
                $('#kode_brand').val(data.kode_brand);
                $('#brandPerangkatForm').append(`<input type="hidden" id="id-input-brand" value="${data.id}">`);
                openBrandPerangkatModal();
            }
        });
    }

    // Delete Brand Perangkat
    window.deleteBrandPerangkat = function(id) {
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
                    url: `/delete-brandperangkat/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            swal("Terhapus!", "Brand berhasil dihapus.", "success");
                            window.location.reload();
                        }
                    }
                });
            }
        });
    }
});

// Alert function
function showSwal(type, message) {
    swal({
        title: type === 'success' ? "Berhasil!" : "Error!",
        text: message,
        type: type,
        button: {
            text: "OK",
            value: true,
            visible: true,
            className: `btn btn-${type === 'success' ? 'primary' : 'danger'}`
        }
    });
}
</script>
@endsection