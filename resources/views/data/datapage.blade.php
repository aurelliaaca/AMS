@extends('layouts.sidebar')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/general.css') }}">
<link rel="stylesheet" href="{{ asset('css/kotak.css') }}">

@section('content')
<div class="main">
    <div class="container">
        <div class="dashboard-header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data</h3>
        </div>

        <div class="kotak-container">
            <!-- Region -->
            <div class="kotak">
                <div class="kotak-header">
                    <div class="icon-wrapper">
                    <i class="fa-solid fa-city"></i>
                    </div>
                    <div class="header-text">
                        <h3>Region</h3>
                    </div>
                </div>
                <div class="kotak-body">
                    <p>Region dan Site</p>
                    <button class="view-btn" onclick="window.location.href='/data/region'">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Perangkat -->
            <div class="kotak" onclick="window.location.href='/data/perangkat'" style="cursor: pointer;">
                <div class="kotak-header">
                    <div class="icon-wrapper">
                    <i class="fas fa-laptop"></i>
                    </div>
                    <div class="header-text">
                        <h3>Perangkat</h3>
                    </div>
                </div>
                <div class="kotak-body">
                    <p>Jenis dan Brand Perangkat</p>
                    <div class="view-btn">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Fasilitas -->
            <div class="kotak">
                <div class="kotak-header">
                    <div class="icon-wrapper">
                    <i class="fas fa-tools"></i>
                    </div>
                    <div class="header-text">
                        <h3>Fasilitas</h3>
                    </div>
                </div>
                <div class="kotak-body">
                    <p>Jenis dan Brand Fasilitas</p>
                    <button class="view-btn" onclick="window.location.href='/data/fasilitas'">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Alat Ukur -->
            <div class="kotak" onclick="window.location.href='/data/alatukur'" style="cursor: pointer;">
                <div class="kotak-header">
                    <div class="icon-wrapper">
                    <i class="fas fa-ruler-combined"></i>
                    </div>
                    <div class="header-text">
                        <h3>Alat Ukur</h3>
                    </div>
                </div>
                <div class="kotak-body">
                    <p>Jenis dan Brand Alat Ukur</p>
                    <div class="view-btn">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection
