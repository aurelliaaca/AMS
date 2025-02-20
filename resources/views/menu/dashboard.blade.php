@extends('layouts.sidebar')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
</head>

<div class="main">
    <div class="container">
        <div class="header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Dasbor</h3>
        </div>
        <div class="card-grid">
            <div class="card-counter device-icon">
            <i class="fa-solid fa-city"></i>
            <div class="count-numbers">{{ $regionCount }}</div>
                <div class="count-name">Region</div>
            </div>

            <div class="card-counter pop-icon">
                <i class="fa-solid fa-building"></i>
                <div class="count-numbers">{{ $popCount }}</div>
                <div class="count-name">POP</div>
            </div>

            <div class="card-counter facility-icon">
                <i class="fa-solid fa-building-user"></i>
                <div class="count-numbers">{{ $pocCount }}</div>
                <div class="count-name">POC</div>
            </div>

            <div class="card-counter rack-icon">
                <i class="fas fa-server"></i>
                <div class="count-numbers">{{ $totalRacksPOP }}</div>
                <div class="count-name">Rack POP</div>
            </div>

            <div class="card-counter rack-icon">
                <i class="fas fa-server"></i>
                <div class="count-numbers">{{ $totalRacksPOC }}</div>
                <div class="count-name">Rack POC</div>
            </div>

            <div class="card-counter device-icon">
                <i class="fas fa-laptop"></i>
                <div class="count-numbers">{{ $perangkatCount }}</div>
                <div class="count-name">Perangkat</div>
            </div>

            <div class="card-counter facility-icon">
                <i class="fas fa-tools"></i>
                <div class="count-numbers">{{ $fasilitasCount }}</div>
                <div class="count-name">Fasilitas</div>
            </div>

            <div class="card-counter">
                <i class="fas fa-ruler-combined"></i>
                <div class="count-numbers">{{ $alatukurCount }}</div>
                <div class="count-name">Alat Ukur</div>
            </div>

            <div class="card-counter">
                <i class="fas fa-circle-nodes"></i>
                <div class="count-numbers">{{ $jaringanCount }}</div>
                <div class="count-name">Jaringan</div>
            </div>
        </div>
    </div>
</div>
@endsection
