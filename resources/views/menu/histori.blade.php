@extends('layouts.sidebar')
@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Inter", sans-serif;
    }

    .container {
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .cards-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-wrapper i {
        font-size: 24px;
        color: white;
    }

    .pop-icon { background: linear-gradient(135deg, #4f52ba, #6366F1); }
    .facility-icon { background: linear-gradient(135deg, #2DD4BF, #14B8A6); }
    .device-icon { background: linear-gradient(135deg, #F59E0B, #D97706); }
    .rack-icon { background: linear-gradient(135deg, #EC4899, #DB2777); }

    .header-text {
        flex-grow: 1;
    }

    .header-text h3 {
        color: #2c3e50;
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 5px 0;
    }

    .count {
        font-size: 24px;
        font-weight: 700;
        color: #4f52ba;
    }

    .card-body {
        padding: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .card-body p {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .view-btn {
        width: 100%;
        background: #f8fafc;
        color: #4f52ba;
        border: 1px solid #e2e8f0;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .view-btn:hover {
        background: #4f52ba;
        color: white;
        border-color: #4f52ba;
    }

    .view-btn i {
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .view-btn:hover i {
        transform: translateX(4px);
    }

    .detail-content {
        margin-top: 30px;
    }

    .back-section {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 15px;
    }

    .back-btn {
        background: #4f52ba;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: #6366F1;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-size: 18px;
        color: #4f52ba;
    }

    .page-title i {
        font-size: 24px;
    }

    .table-container {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        background-color: transparent;
    }

    table thead th {
        color: #4f52ba;
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        font-weight: 600;
        padding: 15px;
        text-align: left;
        font-size: 14px;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    table tbody td {
        padding: 15px;
        border-bottom: 1px solid #e3e6f0;
        color: #5a5c69;
        font-size: 14px;
        vertical-align: middle;
    }

    table tbody tr:hover {
        background-color: #f1f5f9;
        transition: background-color 0.3s ease;
    }

    table tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid;
        text-transform: capitalize;
    }

    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        .cards-container {
            grid-template-columns: 1fr;
        }
        .back-section {
            flex-direction: column;
            align-items: flex-start;
        }
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
    }

    .text-center {
        text-align: center;
        padding: 20px;
        color: #4f52ba;
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-info {
        background-color: #e0f2fe;
        color: #075985;
        border: 1px solid #bae6fd;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .text-success {
        color: #10B981 !important; /* Hijau untuk Di Tambah */
    }

    .text-primary {
        color: #4f52ba !important; /* Warna 4f52ba untuk Di Edit */
    }

    .text-danger {
        color: #EF4444 !important; /* Merah untuk Di Hapus */
    }

    .text-secondary {
        color: #6B7280 !important; /* Warna default jika diperlukan */
    }

    .border-success {
        border-color: #10B981 !important;
    }

    .border-primary {
        border-color: #4f52ba !important;
    }

    .border-danger {
        border-color: #EF4444 !important;
    }

    .border-secondary {
        border-color: #6B7280 !important;
    }

    .bg-white {
        background-color: #ffffff !important;
    }
</style>

<div class="main">
    <div class="container">
        <div class="dashboard-header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Histori</h3>
        </div>

        <!-- Main Content (Cards) -->
        <div id="mainContent" class="cards-container">
            <!-- Card Perangkat -->
            <div class="card">
                <div class="card-header">
                    <div class="icon-wrapper device-icon">
                        <i class="material-symbols-outlined">construction</i>
                    </div>
                    <div class="header-text">
                        <h3>Perangkat</h3>
                        <span class="count">{{ $perangkatCount }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Histori Perangkat</p>
                    <button class="view-btn" onclick="showDetail('perangkat')">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Card Fasilitas -->
            <div class="card">
                <div class="card-header">
                    <div class="icon-wrapper facility-icon">
                        <i class="material-symbols-outlined">domain</i>
                    </div>
                    <div class="header-text">
                        <h3>Fasilitas</h3>
                        <span class="count">{{ $fasilitasCount }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Histori Fasilitas</p>
                    <button class="view-btn" onclick="showDetail('fasilitas')">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Card Jaringan -->
            <div class="card">
                <div class="card-header">
                    <div class="icon-wrapper pop-icon">
                        <i class="material-symbols-outlined">hub</i>
                    </div>
                    <div class="header-text">
                        <h3>Jaringan</h3>
                        <span class="count">{{ $jaringanCount }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Histori Jaringan</p>
                    <button class="view-btn" onclick="showDetail('jaringan')">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Card Alat Ukur -->
            <div class="card">
                <div class="card-header">
                    <div class="icon-wrapper rack-icon">
                        <i class="material-symbols-outlined">square_foot</i>
                    </div>
                    <div class="header-text">
                        <h3>Alat Ukur</h3>
                        <span class="count">{{ $alatukurCount }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Histori Alat Ukur</p>
                    <button class="view-btn" onclick="showDetail('alatukur')">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Detail Content -->
        <div class="detail-content" id="detailContent" style="display: none;">
            <div class="back-section">
                <button class="back-btn" onclick="showMain()">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </button>
                <h2 class="page-title" id="detailTitle"></h2>
            </div>
            <div id="detailData"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
function showDetail(type) {
    console.log('Showing detail for:', type);
    const mainContent = document.getElementById('mainContent');
    const detailContent = document.getElementById('detailContent');
    const detailTitle = document.getElementById('detailTitle');

    mainContent.style.display = 'none';
    detailContent.style.display = 'block';
    
    setTimeout(() => {
        detailContent.classList.add('active');
    }, 50);

    // Set judul dan URL yang benar
    let title = '';
    let icon = '';
    let url = '';
    switch(type) {
        case 'perangkat':
            icon = 'construction';
            title = 'Data Histori Perangkat';
            url = '/get-history-perangkat';
            break;
        case 'fasilitas':
            icon = 'domain';
            title = 'Data Histori Fasilitas';
            url = '/histori/fasilitas';
            break;
        case 'jaringan':
            icon = 'hub';
            title = 'Data Histori Jaringan';
            url = '/histori/jaringan';
            break;
        case 'alatukur':
            icon = 'square_foot';
            title = 'Data Histori Alat Ukur';
            url = '/histori/alatukur';
            break;
    }

    detailTitle.innerHTML = `
        <i class="material-symbols-outlined">${icon}</i>
        <span>${title}</span>
    `;

    loadHistoryData(url, type);
}

function getTextClass(aksi) {
    switch(aksi?.toLowerCase()) {
        case 'ditambah':
            return 'text-success';
        case 'diedit':
            return 'text-primary';
        case 'dihapus':
            return 'text-danger';
        default:
            return 'text-secondary';
    }
}

function loadHistoryData(url, type) {
    const detailData = document.getElementById('detailData');
    detailData.innerHTML = '<div class="text-center">Loading...</div>';

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            console.log('Response:', response);
            
            if (response.success) {
                if (response.data.length === 0) {
                    detailData.innerHTML = '<div class="alert alert-info">Tidak ada data history</div>';
                    return;
                }

                let tableRows = '';
                response.data.forEach((item, index) => {
                    const textClass = getTextClass(item.aksi);
                    const tanggal = new Date(item.tanggal_perubahan).toLocaleString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    if (type === 'perangkat') {
                        tableRows += `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td>${item.region || '-'}</td>
                                <td>${item.site || '-'}</td>
                                <td>${item.nama_perangkat || '-'}</td>
                                <td>${item.brand || '-'}</td>
                                <td>${item.type || '-'}</td>
                                <td>${item.no_rack || '-'}</td>
                                <td>${item.uawal || '-'}</td>
                                <td>${item.uakhir || '-'}</td>
                                <td>${tanggal}</td>
                                <td class="text-center ${textClass}">
                                    ${capitalizeFirstLetter(item.aksi || '-')}
                                </td>
                            </tr>
                        `;
                    } else if (type === 'jaringan') {
                        tableRows += `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td>${item.RO || '-'}</td>
                                <td>${item.tipe_jaringan || '-'}</td>
                                <td>${item.segmen || '-'}</td>
                                <td>${item.jartatup_jartaplok || '-'}</td>
                                <td>${item.mainlink_backuplink || '-'}</td>
                                <td>${item.panjang || '-'}</td>
                                <td>${item.panjang_drawing || '-'}</td>
                                <td>${item.jumlah_core || '-'}</td>
                                <td>${item.jenis_kabel || '-'}</td>
                                <td>${item.tipe_kabel || '-'}</td>
                                <td>${tanggal}</td>
                                <td class="text-center ${textClass}">
                                    ${capitalizeFirstLetter(item.aksi || '-')}
                                </td>
                            </tr>
                        `;
                    }
                });

                if (type === 'perangkat') {
                    detailData.innerHTML = `
                        <div class="table-container">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">No</th>
                                            <th style="width: 10%">Region</th>
                                            <th style="width: 10%">Site</th>
                                            <th style="width: 10%">Nama Perangkat</th>
                                            <th style="width: 10%">Brand</th>
                                            <th style="width: 10%">Type</th>
                                            <th style="width: 10%">No Rack</th>
                                            <th style="width: 10%">U Awal</th>
                                            <th style="width: 10%">U Akhir</th>
                                            <th style="width: 10%">Tanggal</th>
                                            <th class="text-center" style="width: 15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tableRows}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                } else if (type === 'jaringan') {
                    detailData.innerHTML = `
                        <div class="table-container">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">No</th>
                                            <th style="width: 10%">RO</th>
                                            <th style="width: 10%">Tipe Jaringan</th>
                                            <th style="width: 10%">Segmen</th>
                                            <th style="width: 10%">Jartatup/Jartaplok</th>
                                            <th style="width: 10%">Mainlink/Backuplink</th>
                                            <th style="width: 10%">Panjang</th>
                                            <th style="width: 10%">Panjang Drawing</th>
                                            <th style="width: 10%">Jumlah Core</th>
                                            <th style="width: 10%">Jenis Kabel</th>
                                            <th style="width: 10%">Tipe Kabel</th>
                                            <th style="width: 10%">Tanggal</th>
                                            <th class="text-center" style="width: 15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tableRows}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                }
            } else {
                console.error('Failed to fetch history data:', response.message);
                detailData.innerHTML = '<div class="alert alert-danger">Gagal memuat data history</div>';
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax Error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            detailData.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memuat data</div>';
        }
    });
}

function capitalizeFirstLetter(string) {
    if (!string) return '-';
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function showMain() {
    const mainContent = document.getElementById('mainContent');
    const detailContent = document.getElementById('detailContent');
    
    mainContent.style.display = 'grid';
    detailContent.style.display = 'none';
}
</script>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}">