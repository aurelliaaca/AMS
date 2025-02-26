@extends('layouts.sidebar')
@section('content')
<link rel="stylesheet" href="{{ asset('css/kotak.css') }}">
<link rel="stylesheet" href="{{ asset('css/general.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
<script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="main">
    <div class="container">
        <div class="dashboard-header" id="mainHeader">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Histori</h3>
        </div>

        <div id="mainContent" class="kotak-containerhistori">
            <div class="kotak">
                <div class="kotak-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div class="header-text">
                        <h3>Perangkat</h3>
                        <span class="count">{{ $perangkatCount }}</span>
                    </div>
                </div>
                <div class="kotak-body">
                    <p>Total Histori Perangkat</p>
                    <button class="view-btn" onclick="showDetail('perangkat')">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="kotak">
                <div class="kotak-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="header-text">
                        <h3>Fasilitas</h3>
                        <span class="count">{{ $fasilitasCount }}</span>
                    </div>
                </div>
                <div class="kotak-body">
                    <p>Total Histori Fasilitas</p>
                    <button class="view-btn" onclick="showDetail('fasilitas')">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="kotak">
                <div class="kotak-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <div class="header-text">
                        <h3>Alat Ukur</h3>
                        <span class="count">{{ $alatukurCount }}</span>
                    </div>
                </div>
                <div class="kotak-body">
                    <p>Total Histori Alat Ukur</p>
                    <button class="view-btn" onclick="showDetail('alatukur')">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="kotak">
                <div class="kotak-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-circle-nodes"></i>
                    </div>
                    <div class="header-text">
                        <h3>Jaringan</h3>
                        <span class="count">{{ $jaringanCount }}</span>
                    </div>
                </div>
                <div class="kotak-body">
                    <p>Total Histori Jaringan</p>
                    <button class="view-btn" onclick="showDetail('jaringan')">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="detail-content" id="detailContent" style="display: none;">
            <div class="back-section">
                <button class="back-btn" onclick="showMain()">
                    <i class="fas fa-arrow-left"></i>
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
    document.getElementById("mainHeader").style.display = "none"; // Sembunyikan header
    document.getElementById("detailContent").style.display = "block"; // Tampilkan detail
    
    console.log('Showing detail for:', type);
    const mainContent = document.getElementById('mainContent');
    const detailContent = document.getElementById('detailContent');
    const detailTitle = document.getElementById('detailTitle');

    mainContent.style.display = 'none';
    detailContent.style.display = 'block';

    setTimeout(() => detailContent.classList.add('active'), 50);

    const dataMapping = {
        perangkat: { title: 'Data Histori Perangkat', url: '/get-history-perangkat' },
        fasilitas: { title: 'Data Histori Fasilitas', url: '/get-history-fasilitas' },
        alatukur: { title: 'Data Histori Alat Ukur', url: '/get-history-alatukur' },
        jaringan: { title: 'Data Histori Jaringan', url: '/get-history-jaringan' },
    };

    if (!dataMapping[type]) return console.error('Invalid type:', type);

    detailTitle.innerHTML = `
        <span style="font-size: 18px; font-weight: 600; color: #4f52ba; padding: 5px;">
            ${dataMapping[type].title}
        </span>
    `;

    loadHistoryData(dataMapping[type].url, type);
}

function generateKodePerangkat(item) {
    return [
        item?.kode_region, 
        item?.kode_site, 
        item?.no_rack, 
        item?.kode_perangkat, 
        item?.perangkat_ke, 
        item?.kode_brand, 
        item?.type
    ].filter(val => val).join('-');
}

function generateKodeFasilitas(item) {
    return [
        item?.kode_region, 
        item?.kode_site, 
        item?.no_rack, 
        item?.kode_fasilitas, 
        item?.fasilitas_ke, 
        item?.kode_brand, 
        item?.type
    ].filter(val => val).join('-');
}

function generateKodeAlatukur(item) {
    return [
        item?.kode_region, 
        item?.kode_alatukur, 
        item?.alatukur_ke, 
        item?.kode_brand, 
        item?.type
    ].filter(val => val).join('-');
}

function getDataFields(type, item) {
    return {
        perangkat: [
            generateKodePerangkat(item), item.nama_region, item.nama_site, 
            item.nama_perangkat, item.perangkat_ke, 
            item.nama_brand, item.type, item.no_rack, 
            item.uawal, item.uakhir, item.histori
        ],
        fasilitas: [
            generateKodeFasilitas(item), item.nama_region, item.nama_site, item.nama_fasilitas, 
            item.fasilitas_ke, item.nama_brand, item.type, 
            item.no_rack, item.serialnumber, item.jml_fasilitas, 
            item.status, item.uawal, item.uakhir, item.histori
        ],
        alatukur: [
            generateKodeAlatukur(item), item.nama_region, item.nama_alatukur, item.nama_brand, 
            item.type, item.serialnumber, item.alatukur_ke, 
            item.tahunperolehan, item.kondisi, item.keterangan, item.histori
        ],
        jaringan: [
            item.RO, item.tipe_jaringan, item.segmen, item.jartatup_jartaplok, 
            item.mainlink_backuplink, item.panjang, item.panjang_drawing, 
            item.jumlah_core, item.jenis_kabel, item.tipe_kabel, 
            item.status, item.ket, item.ket2, item.kode_site_insan, 
            item.update, item.route, item.dci_eqx, item.aksi
        ]
    }[type] || [];
}

function loadHistoryData(url, type) {
    const detailData = document.getElementById('detailData');
    detailData.innerHTML = '<div class="text-center" style="font-size: 18px; font-weight: 600; color: #4f52ba; padding: 5px;">Loading...</div>';

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            console.log('Response:', response);

            if (!response.success || response.data.length === 0) {
                detailData.innerHTML = '<div class="text-center" style="font-size: 18px; font-weight: 600; color: #4f52ba; padding: 5px;">Tidak ada data history</div>';
                return;
            }

            let tableRows = response.data.map((item, index) => {
                const tanggal = new Date(item.tanggal_perubahan).toLocaleString('id-ID', {
                    day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
                });

                return `<tr>
                    <td class="text-center">${index + 1}</td>
                    ${getDataFields(type, item).map(field => `<td>${field || '-'}</td>`).join('')}
                    <td>${tanggal}</td>
                </tr>`;
            }).join('');

            detailData.innerHTML = `
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    ${getTableHeaders(type)}
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>${tableRows}</tbody>
                        </table>
                    </div>
                </div>
            `;
        },
        error: function(xhr, status, error) {
            console.error('Ajax Error:', error);
            detailData.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memuat data</div>';
        }
    });
}

function getTableHeaders(type) {
    const headers = {
        perangkat: [
            "Hostname", "Kode Region", "Kode Site", "Kode Perangkat", 
            "Perangkat Ke", "Kode Brand", "Type", "No Rack", "U Awal", 
            "U Akhir", "Histori"
        ],
        fasilitas: [
            "Hostname", "Kode Region", "Kode Site", "Kode Fasilitas", 
            "Fasilitas Ke", "Kode Brand", "Type", "No Rack", 
            "Serial Number", "Jumlah Fasilitas", "Status", "U Awal", 
            "U Akhir", "Histori"
        ],
        alatukur: [
            "Hostname", "Kode Region", "Kode Alat Ukur", "Kode Brand", 
            "Type", "Serial Number", "Alat Ukur Ke", "Tahun Perolehan", 
            "Kondisi", "Keterangan", "Histori"
        ],
        jaringan: [
            "RO", "Tipe Jaringan", "Segmen", "Jartatup/Jartaplok", 
            "Mainlink/Backuplink", "Panjang", "Panjang Drawing", 
            "Jumlah Core", "Jenis Kabel", "Tipe Kabel", "Status", 
            "Keterangan", "Keterangan 2", "Kode Site Insan", 
            "Update", "Route", "DCI/EQX", "Aksi"
        ]
    };
    return headers[type]?.map(title => `<th>${title}</th>`).join('') || '';
}


function capitalizeFirstLetter(string) {
    return string ? string.charAt(0).toUpperCase() + string.slice(1) : '-';
}

function showMain() {
    document.getElementById('mainContent').style.display = 'grid';
    document.getElementById('detailContent').style.display = 'none';
}
</script>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}">