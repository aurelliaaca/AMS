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
        width: 95%;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        margin-bottom: 20px;
    }

    .header h2 {
        color: #4f52ba;
        font-size: 24px;
        font-weight: 600;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 300px));
        gap: 20px;
        padding: 10px;
        justify-content: center;
    }

    .card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .card.region::before {
        background: #4f52ba;
    }

    .card.pop::before {
        background: #28a745;
    }

    .card-icon {
        font-size: 40px;
        margin-bottom: 15px;
        color: #4f52ba;
    }

    .card-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .card-count {
        font-size: 24px;
        font-weight: 700;
        color: #4f52ba;
        margin-bottom: 10px;
    }

    .card-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
    }

    .card-link {
        color: #4f52ba;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .card-link:hover {
        background-color: #f0f0f0;
    }
</style>

<div class="container">
    <div class="header">
    <h2 style= color: #4f52ba;>Data Overview</h2>
    </div>
    
    <div class="card-grid">
        <!-- Region Card -->
        <div class="card region">
            <div class="card-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3 class="card-title">Region</h3>
            <div class="card-count">{{ $regionCount }}</div>
            <a href="{{ route('data.region') }}" class="card-link">Lihat Detail <i class="fas fa-arrow-right"></i></a>
        </div>

        <!-- POP Card -->
        <div class="card pop">
            <div class="card-icon">
                <i class="fas fa-network-wired"></i>
            </div>
            <h3 class="card-title">POP</h3>
            <div class="card-count">{{ $popCount }}</div>
            <p class="card-description">Point of Presence aktif</p>
            <a href="{{ route('data.pop') }}" class="card-link">Lihat Detail <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection