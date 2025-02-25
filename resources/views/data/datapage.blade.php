@extends('layouts.sidebar')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

@section('content')
<div class="main">
    <div class="container">
        <div class="dashboard-header">
        <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data</h3>
        </div>

        <div class="cards-container">
            <!-- Card Region -->
            <div class="card">
                <div class="card-header">
                <div class="icon-wrapper device-icon">
                    <span class="material-symbols-outlined">distance</span>
                </div>
                    <div class="header-text">
                        <h3>Region</h3>
                        <span class="count">{{ $regionCount}}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Jumlah Region</p>
                    <button class="view-btn" onclick="window.location.href='/data/region'">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Card POP -->
            <div class="card">
                <div class="card-header">
                    <div class="icon-wrapper pop-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="header-text">
                        <h3>POP</h3>
                        <span class="count">{{ $popCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Point of Presence</p>
                    <button class="view-btn" onclick="window.location.href='/data/pop'">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Card POP -->
            <div class="card">
                <div class="card-header">
                    <div class="icon-wrapper pop-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="header-text">
                        <h3>POC</h3>
                        <span class="count">{{ $pocCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Point of </p>
                    <button class="view-btn" onclick="window.location.href='/data/poc'">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>


            <!-- Card Fasilitas -->
            <div class="card">
                <div class="card-header">
                <div class="icon-wrapper device-icon">
                    <span class="material-symbols-outlined">domain</span>
                </div>
                    <div class="header-text">
                        <h3>Fasilitas</h3>
                        <span class="count">{{ $fasilitasCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Fasilitas Tersedia</p>
                    <button class="view-btn" onclick="window.location.href='/data/fasilitas'">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Card Perangkat -->
            <div class="card" onclick="window.location.href='/data/perangkat'" style="cursor: pointer;">
                <div class="card-header">
                    <div class="icon-wrapper device-icon">
                        <span class="material-symbols-outlined">construction</span>
                    </div>
                    <div class="header-text">
                        <h3>Perangkat</h3>
                        <span class="count">{{ $perangkatCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Perangkat Aktif</p>
                    <div class="view-btn">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>


            <!-- Card Alat Ukur -->
            <div class="card" onclick="window.location.href='/data/dataalatukur'" style="cursor: pointer;">
                <div class="card-header">
                    <div class="icon-wrapper device-icon">
                        <span class="material-symbols-outlined">square_foot</span>
                    </div>
                    <div class="header-text">
                        <h3>Alat Ukur</h3>
                        <span class="count">{{ $alatukurCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Jumlah alat ukur</p>
                    <div class="view-btn">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
<!--       
            Card Rack
            <div class="card">
                <div class="card-header">
                    <div class="icon-wrapper rack-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="header-text">
                        <h3>Rack</h3>
                        <span class="count">{{ $rackCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Total Rack Terpasang</p>
                    <button class="view-btn" onclick="window.location.href='/data/rack'">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div> -->
        </div>
    </div> 
</div>

<style>
/* Container utama dengan padding dan max-width untuk membatasi lebar konten */
.container {
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;  /* Membuat container center */
}

/* Styling untuk header dashboard */
.dashboard-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;  /* Garis pembatas bawah */
}

/* Judul dashboard */
.dashboard-header h1 {
    color: #2c3e50;  /* Warna dark blue */
    font-size: 24px;
    font-weight: 700;  /* Extra bold */
    margin-bottom: 8px;
}

/* Subtitle dashboard */
.dashboard-header p {
    color: #7f8c8d;  /* Warna abu-abu */
    font-size: 14px;
}

/* Grid layout untuk cards dengan responsive columns */
.cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;  /* Jarak antar card */
}

/* Styling untuk setiap card */
.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);  /* Bayangan halus */
    transition: all 0.3s ease;  /* Animasi smooth */
    overflow: hidden;
}

/* Efek hover pada card */
.card:hover {
    transform: translateY(-5px);  /* Card naik saat hover */
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);  /* Bayangan lebih tebal */
}

/* Header dari card */
.card-header {
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Wrapper untuk icon */
.icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Style untuk icon */
.icon-wrapper i {
    font-size: 24px;
    color: white;
}

/* Gradient background untuk setiap icon */
.pop-icon { background: linear-gradient(135deg, #4f52ba, #6366F1); }
.facility-icon { background: linear-gradient(135deg, #2DD4BF, #14B8A6); }
.device-icon { background: linear-gradient(135deg, #F59E0B, #D97706); }
.rack-icon { background: linear-gradient(135deg, #EC4899, #DB2777); }

/* Text container dalam header */
.header-text {
    flex-grow: 1;  /* Mengisi sisa ruang */
}

/* Judul dalam card */
.header-text h3 {
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 5px 0;
}

/* Angka count */
.count {
    font-size: 24px;
    font-weight: 700;
    color: #4f52ba;  /* Warna ungu */
}

/* Body card */
.card-body {
    padding: 20px;
    border-top: 1px solid #f0f0f0;  /* Garis pembatas */
}

/* Teks dalam body */
.card-body p {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 15px;
}

/* Tombol view detail */
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

/* Efek hover pada tombol */
.view-btn:hover {
    background: #4f52ba;
    color: white;
    border-color: #4f52ba;
}

/* Icon dalam tombol */
.view-btn i {
    font-size: 12px;
    transition: transform 0.3s ease;
}

/* Animasi icon saat hover */
.view-btn:hover i {
    transform: translateX(4px);  /* Icon bergeser ke kanan */
}

/* Responsive design untuk layar kecil */
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }
    .cards-container {
        grid-template-columns: 1fr;  /* 1 kolom di mobile */
    }
    .dashboard-header h1 {
        font-size: 20px;  /* Font lebih kecil di mobile */
    }
}

/* Membuat background tetap gradient seperti sebelumnya */
.icon-wrapper {
    width: 60px; /* Membesarkan kotak ikon */
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Membuat ikon menjadi lebih besar dan berwarna putih */
.icon-wrapper .material-symbols-outlined {
    font-size: 36px;  /* Memperbesar ukuran ikon */
    color: white;     /* Mengganti warna ikon menjadi putih */
}

</style>

<!-- Tambahkan Font Awesome untuk icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection