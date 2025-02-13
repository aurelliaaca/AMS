@extends('layouts.sidebar')

@section('content')
<div class="main">
    <div class="container">
                <h3>Informasi Fasilitas</h3>


        <!-- Titles Container -->
        <div class="titles-container">
            <!-- Title Perangkat -->
            <div class="section-title">
                <div class="title-wrapper">
                    <span class="material-symbols-outlined">devices</span>
                    <h4>Nama Fasilitas</h4>
                </div>
                <button type="button" class="add-button" onclick="openAddFasilitasModal()" title="Tambah Fasilitas">
                    <span class="material-symbols-outlined">add</span>
                </button>
            </div>

            <!-- Title Brand -->
            <div class="section-title">
                <div class="title-wrapper">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <h4>Brand Fasilitas</h4>
                </div>
                <button type="button" class="add-button" onclick="openAddBrandFasilitasModal()" title="Tambah Brand">
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
                                <th>Nama Fasilitas</th>
                                <th>Kode Fasilitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($namafasilitas as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->fasilitas }}</td>
                                    <td>{{ $item->kode_fasilitas }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit-btn" title="Edit" onclick="editInfoFasilitas({{ $item->id }})">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>
                                            <button class="delete-btn" title="Hapus" onclick="deleteInfoFasilitas({{ $item->id }})">
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
                        @forelse($brandfasilitas as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_brand }}</td>
                                    <td>{{ $item->kode_brand }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit-btn" title="Edit" onclick="editInfoFasilitas({{ $item->id }})">
                                                <span class="material-symbols-outlined">edit</span>
                                            </button>
                                            <button class="delete-btn" title="Hapus" onclick="deleteInfoFasilitas({{ $item->id }})">
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
        gap: 50px;
    }

    .table-section {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .table-wrapper {
        padding: 20px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    thead th:first-child {
        border-top-left-radius: 15px;
    }

    thead th:last-child {
        border-top-right-radius: 15px;
    }

    tbody tr:last-child td:first-child {
        border-bottom-left-radius: 15px;
    }

    tbody tr:last-child td:last-child {
        border-bottom-right-radius: 15px;
    }

    thead th {
        background: #4f52ba;
        color: #ffff;
        font-size: 14px;
        font-weight: 600;
        padding: 15px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }

    tbody td {
        padding: 15px;
        color: #4a5568;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
    }


    tbody tr:hover {
        background: #f8fafc;
        transition: background-color 0.3s ease;
    }

    /* Tombol Aksi (Edit & Delete) */
    .action-buttons {
        display: flex;
        gap: 8px;
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

    @media (max-width: 1200px) {
        .titles-container,
        .table-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }
    }
</style>

<!-- Link untuk Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

@endsection