@extends('layouts.sidebar')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <head>
        <link rel="stylesheet" href="{{ asset('css/aset.css') }}">
    </head>

<div class="main">
    <div class="container">
        <div class="header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Dasbor</h3>
        </div>
        <div class="card-grid">
            <a href="{{ route('data.region') }}" class="card-counter primary" style="text-decoration: none;">
                <i class="material-symbols-outlined">map</i>
                <span class="count-numbers">{{ $regionCount }}</span>
                <span class="count-name">Region</span>
            </a>

            <a href="{{ route('data.pop') }}" class="card-counter primary" style="text-decoration: none;">
            <i class="material-symbols-outlined">location_on</i>
                <span class="count-numbers">{{ $siteCount }}</span>
                <span class="count-name">Site</span>
            </a>

            <a href="{{ route('rack') }}" class="card-counter primary" style="text-decoration: none;">
                <i class="material-symbols-outlined">dataset</i>
                <span class="count-numbers">{{ $totalRacks }}</span>
                <span class="count-name">Rack</span>
            </a>

            <a href="{{ route('perangkat') }}" class="card-counter primary" style="text-decoration: none;">
                <i class="material-symbols-outlined">construction</i>
                <span class="count-numbers">{{ $perangkatCount }}</span>
                <span class="count-name">Perangkat</span>
            </a>

            <a href="{{ route('fasilitas') }}" class="card-counter primary" style="text-decoration: none;">
                <i class="material-symbols-outlined">domain</i>
                <span class="count-numbers">{{ $fasilitasCount }}</span>
                <span class="count-name">Fasilitas</span>
            </a>

            <a href="{{ route('jaringan') }}" class="card-counter primary" style="text-decoration: none;">
                <i class="material-symbols-outlined">hub</i>
                <span class="count-numbers">{{ $jaringanCount }}</span>
                <span class="count-name">Jaringan</span>
            </a>

            <a href="{{ route('alatukur') }}" class="card-counter primary" style="text-decoration: none;">
                <i class="material-symbols-outlined">square_foot</i>
                <span class="count-numbers">{{ $alatukurCount }}</span>
                <span class="count-name">Alat ukur</span>
            </a>
            
        </div>
    </div>
</div>
@endsection