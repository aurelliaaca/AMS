@extends('layouts.sidebar')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Inter", sans-serif;
    }

    .container {
        width: 100%;
        background-color: #fff;
        padding: 70px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        margin-bottom: 50px;
    }

    .header h3 {
        font-size: 24px;
        font-weight: 600;
        color: #4f52ba;
        margin: 0;
    }

    .card-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .card-counter {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s;
        position: relative;
        min-height: 100px;
        width: 100%;
        margin-bottom: 40px;
    }

    .card-counter .icon-section {
        display: flex;
        align-items: center;
        width: 15%;
    }

    .count-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 40%;
    }

    .button-section {
        width: 25%;
        text-align: right;
    }

    .open-btn {
        padding: 8px 16px;
        border: 2px solid white;
        background: transparent;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .open-btn:hover {
        background: white;
        color: #4f52ba;
    }

    /* Warna tombol sesuai card */
    .primary .open-btn:hover {
        color: #4f52ba;
    }

    .success .open-btn:hover {
        color: #1cc88a;
    }

    .info .open-btn:hover {
        color: #36b9cc;
    }

    .danger .open-btn:hover {
        color: #e74a3b;
    }

    .content-section {
        display: none;
        margin-top: 20px;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .content-section.active {
        display: block;
    }

    .card-counter i {
        font-size: 48px;
        opacity: 0.8;
        margin-right: 20px;
        color: white;
    }

    .count-numbers {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 5px;
        color: white;
    }

    .count-name {
        font-size: 20px;
        color: white;
        font-style: italic;
        opacity: 0.9;
    }

    /* Warna card dengan gradient */
    .primary {
        background: linear-gradient( #4f52ba 0%, #6f86e0 100%);
    }

    .danger {
        background: linear-gradient( #4f52ba 0%, #6f86e0 100%);
    }

    .success {
        background: linear-gradient( #4f52ba 0%, #6f86e0 100%);
    }

    .info {
        background: linear-gradient( #4f52ba 0%, #6f86e0 100%);
    }


    /* Responsive design */
    @media (max-width: 768px) {
        .card-counter {
            padding: 15px;
        }

        .count-numbers {
            font-size: 28px;
        }

        .count-name {
            font-size: 16px;
        }

        .card-counter i {
            font-size: 36px;
        }

        .header {
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .card-grid {
            padding-top: 15px;
        }
    }

    .main-content, .detail-content {
        width: 100%;
        transition: all 0.3s ease;
    }

    .detail-content {
        display: none;
    }

    .detail-content.active {
        display: block;
    }

    .back-section {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding: 10px 0;
        border-bottom: 2px solid #eef2f7;
        position: relative;
    }

    .back-btn {
        position: absolute;
        left: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: linear-gradient(45deg, #4f52ba 0%, #6f86e0 100%);
        color: white;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(79, 82, 186, 0.2);
        z-index: 2;
    }

    .back-btn:hover {
        transform: translateX(-5px);
        box-shadow: 0 5px 15px rgba(79, 82, 186, 0.3);
    }

    .back-btn i {
        font-size: 20px;
        transition: all 0.3s ease;
    }

    .back-btn:hover i {
        transform: translateX(-3px);
    }

    .page-title {
        width: 100%;
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        color: #4f52ba;
        margin: 0;
        padding: 0 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .page-title i {
        font-size: 28px;
        vertical-align: middle;
    }

    /* Animasi untuk title */
    .page-title {
        opacity: 0;
        transform: translateY(-10px);
        animation: fadeInDown 0.5s ease forwards;
    }

    @keyframes fadeInDown {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animasi untuk transisi halaman */
    .detail-content {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }

    .detail-content.active {
        opacity: 1;
        transform: translateY(0);
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
    }

    table tbody td {
        padding: 15px;
        border-bottom: 1px solid #e3e6f0;
        color: #5a5c69;
        font-size: 14px;
        vertical-align: middle;
    }

    table tbody tr:hover {
        background-color: #f8f9fc;
    }

    /* Stripe effect */
    table tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    /* Responsive table */
    @media (max-width: 768px) {
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
    }

    /* Tambahan CSS untuk badge */
    .badge {
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 500;
        border-radius: 4px;
        text-transform: capitalize;
        border: 1px solid;
    }

    .text-success {
        color: #28a745 !important;
    }

    .border-success {
        border-color: #28a745 !important;
    }

    .text-primary {
        color: #4f52ba !important;
    }

    .border-primary {
        border-color: #4f52ba !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .border-danger {
        border-color: #dc3545 !important;
    }

    .text-secondary {
        color: #6c757d !important;
    }

    .border-secondary {
        border-color: #6c757d !important;
    }

    .bg-white {
        background-color: #ffffff !important;
    }

    .table th {
        background-color: #f8f9fa;
        color: #4f52ba;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .table td {
        vertical-align: middle;
        padding: 12px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(79, 82, 186, 0.05);
    }

    /* Loading spinner */
    .text-center {
        text-align: center;
    }

    /* Alert styles */
    .alert {
        padding: 12px 20px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    /* Custom badge styles */
    .bg-success {
        background-color: #28a745 !important;
        color: white;
    }

    .bg-primary {
        background-color: #4f52ba !important;
        color: white;
    }

    .bg-danger {
        background-color: #dc3545 !important;
        color: white;
    }

    .bg-secondary {
        background-color: #6c757d !important;
        color: white;
    }
</style>

<div class="main">
    <div class="container">
        <!-- Halaman Utama -->
        <div class="main-content" id="mainContent">
            <div class="header">
                <h3>Histori</h3>
            </div>
            <div class="card-grid">
                <div class="card-counter primary">
                    <div class="icon-section">
                        <i class="material-symbols-outlined">construction</i>
                    </div>
                    <div class="count-section">
                        <span class="count-numbers">{{ $perangkatCount }}</span>
                        <span class="count-name">Perangkat</span>
                    </div>
                    <div class="button-section">
                        <button class="open-btn" onclick="showDetail('perangkat')">Buka Disini</button>
                    </div>
                </div>
                
                <div class="card-counter success">
                    <div class="icon-section">
                        <i class="material-symbols-outlined">domain</i>
                    </div>
                    <div class="count-section">
                        <span class="count-numbers">{{ $fasilitasCount }}</span>
                        <span class="count-name">Fasilitas</span>
                    </div>
                    <div class="button-section">
                        <button class="open-btn" onclick="showDetail('fasilitas')">Buka Disini</button>
                    </div>
                </div>
                
                <div class="card-counter info">
                    <div class="icon-section">
                        <i class="material-symbols-outlined">hub</i>
                    </div>
                    <div class="count-section">
                        <span class="count-numbers">{{ $jaringanCount }}</span>
                        <span class="count-name">Jaringan</span>
                    </div>
                    <div class="button-section">
                        <button class="open-btn" onclick="showDetail('jaringan')">Buka Disini</button>
                    </div>
                </div>
                
                <div class="card-counter danger">
                    <div class="icon-section">
                        <i class="material-symbols-outlined">square_foot</i>
                    </div>
                    <div class="count-section">
                        <span class="count-numbers">{{ $alatukurCount }}</span>
                        <span class="count-name">Alat ukur</span>
                    </div>
                    <div class="button-section">
                        <button class="open-btn" onclick="showDetail('alatukur')">Buka Disini</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Halaman Detail -->
        <div class="detail-content" id="detailContent">
            <div class="back-section">
                <button class="back-btn" onclick="showMain()">
                    <i class="material-symbols-outlined">arrow_back</i>
                    <span>Kembali</span>
                </button>
                <h2 class="page-title" id="detailTitle"></h2>
            </div>
            <div id="detailData"></div>
        </div>
    </div>
</div>

<script>
function showDetail(type) {
    console.log('Showing detail for:', type);
    const mainContent = document.getElementById('mainContent');
    const detailContent = document.getElementById('detailContent');
    const detailTitle = document.getElementById('detailTitle');
    const detailData = document.getElementById('detailData');

    mainContent.style.display = 'none';
    detailContent.style.display = 'block';
    
    setTimeout(() => {
        detailContent.classList.add('active');
    }, 50);

    // Set judul
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
            url = '/get-history-fasilitas';
            break;
        case 'jaringan':
            icon = 'hub';
            title = 'Data Histori Jaringan';
            url = '/get-history-jaringan';
            break;
        case 'alatukur':
            icon = 'square_foot';
            title = 'Data Histori Alat Ukur';
            url = '/get-history-alatukur';
            break;
    }

    detailTitle.innerHTML = `
        <i class="material-symbols-outlined">${icon}</i>
        <span>${title}</span>
    `;

    loadHistoryData(url);
}

function loadHistoryData(url) {
    const detailData = document.getElementById('detailData');
    
    // Tambahkan console.log untuk debugging
    console.log('Loading history data from:', url);
    
    // Tampilkan loading
    detailData.innerHTML = '<div class="text-center">Loading...</div>';
    
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            // Debug response
            console.log('Response:', response);
            
            if (response.success) {
                if (response.data.length === 0) {
                    detailData.innerHTML = '<div class="alert alert-info">Tidak ada data history</div>';
                    return;
                }

                let tableRows = '';
                response.data.forEach((item, index) => {
                    // Debug setiap item
                    console.log('Processing item:', item);
                    
                    // Set warna badge berdasarkan aksi
                    const badgeClass = getBadgeClass(item.aksi);
                    
                    // Format tanggal
                    const tanggal = new Date(item.tanggal_perubahan).toLocaleString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    tableRows += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td> ${item.region || '-'}</td>
                            <td> ${item.site || '-'}</td>
                            <td>${item.nama_perangkat || '-'}</td>
                            <td> ${item.brand || '-'}</td>
                            <td> ${item.type || '-'}</td>
                            <td> ${item.no_rack || '-'}</td>
                            <td> ${item.uawal || '-'}</td>
                            <td> ${item.uakhir || '-'}</td>
                            <td>${tanggal}</td>
                            <td class="text-center">
                                <span class="badge ${badgeClass}">
                                    ${capitalizeFirstLetter(item.aksi || '-')}
                                </span>
                            </td>
                        </tr>
                    `;
                });

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
                                        <th style="width: 10%">U Akhir  </th>
                                        <th style="width: 10%">Tanggal</th>
                                        <th class="text-center" style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${tableRows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
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

function getBadgeClass(aksi) {
    switch(aksi?.toLowerCase()) {
        case 'tambah':
            return 'badge text-success bg-white border-success';
        case 'edit':
            return 'badge text-primary bg-white border-primary';
        case 'hapus':
            return 'badge text-danger bg-white border-danger';
        default:
            return 'badge text-secondary bg-white border-secondary';
    }
}

function capitalizeFirstLetter(string) {
    if (!string) return '-';
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function showMain() {
    const mainContent = document.getElementById('mainContent');
    const detailContent = document.getElementById('detailContent');
    
    detailContent.classList.remove('active');
    
    setTimeout(() => {
        detailContent.style.display = 'none';
        mainContent.style.display = 'block';
    }, 300);
}
</script>

<!-- Tambahkan ini di bagian head atau sebelum closing body -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection