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
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .header {
        margin-bottom: 10px;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
    }

    .card-counter {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end; /* Menyusun konten ke bawah kanan */
        align-items: flex-end;     /* Menyusun elemen ke kanan */
        transition: transform 0.2s;
        position: relative;  /* Untuk positioning icon */
        overflow: hidden;    /* Menyembunyikan bagian icon yang keluar */
        min-height: 140px;   /* Memberikan tinggi minimum */
    }

    .card-counter i {
        position: absolute;  /* Positioning absolute untuk icon */
        top: 50%;            /* Menempatkan icon di tengah vertikal */
        left: 0px;           /* Posisi dari kiri */
        transform: translateY(-50%); /* Menggeser icon agar tepat di tengah vertikal */
        font-size: 125px;     /* Ukuran icon yang lebih besar */
        opacity: 0.2;         /* Transparansi icon */
        z-index: 1;           /* Memastikan icon di belakang teks */
    }


    .count-numbers {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 5px;
        position: relative;  /* Memastikan teks di atas icon */
        z-index: 2;
        text-align: right;   /* Menyusun teks ke kanan */
    }

    .count-name {
        font-size: 24px;
        color: inherit;     /* Mengikuti warna parent */
        position: relative; /* Memastikan teks di atas icon */
        z-index: 2;
        text-align: right;   /* Menyusun teks ke kanan */
        font-style: italic;  /* Menambahkan efek italic */
        opacity: 0.6;  
        bottom: 15px;   
    }

    /* Warna card dengan opacity yang lebih solid */
    .primary {
        background: linear-gradient(45deg, #4f52ba 0%, #6f86e0 100%);
        color: white;
    }

    .danger {
        background: linear-gradient(45deg, #e74a3b 0%, #e95d4f 100%);
        color: white;
    }

    .success {
        background: linear-gradient(45deg, #1cc88a 0%, #2fdba3 100%);
        color: white;
    }

    .info {
        background: linear-gradient(45deg, #36b9cc 0%, #4dc4d4 100%);
        color: white;
    }

    @media (max-width: 1200px) {
        .card-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 900px) {
        .card-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 600px) {
        .card-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main">
    <div class="container">
        <div class="header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Dashboard</h3>
        </div>
        <div class="card-grid">
            <a href="{{ route('pengaturan') }}" class="card-counter primary" style="text-decoration: none;">
                <i class="material-symbols-outlined">map</i>
                <span class="count-numbers">{{ $regionCount }}</span>
                <span class="count-name">Region</span>
            </a>

            
            <div class="card-counter primary">
                <i class="material-symbols-outlined">location_on</i>
                <span class="count-numbers">{{ $siteCount }}</span>
                <span class="count-name">Site</span>
            </div>
            
            <div class="card-counter primary">
                <i class="material-symbols-outlined">dataset</i>
                <span class="count-numbers">{{ $totalRacks }}</span>
                <span class="count-name">Rack</span>
            </div>

            <div class="card-counter primary">
                <i class="material-symbols-outlined">construction</i>
                <span class="count-numbers">{{ $perangkatCount }}</span>
                <span class="count-name">Perangkat</span>
            </div>
            
            <div class="card-counter primary">
                <i class="material-symbols-outlined">domain</i>
                <span class="count-numbers">{{ $fasilitasCount }}</span>
                <span class="count-name">Fasilitas</span>
            </div>
            
            <div class="card-counter primary">
                <i class="material-symbols-outlined">hub</i>
                <span class="count-numbers">{{ $jaringanCount }}</span>
                <span class="count-name">Jaringan</span>
            </div>
            
            <div class="card-counter primary">
                <i class="material-symbols-outlined">square_foot</i>
                <span class="count-numbers">{{ $alatukurCount }}</span>
                <span class="count-name">Alat ukur</span>
            </div>
        </div>
    </div>
</div>
@endsection