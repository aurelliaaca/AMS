@extends('layouts.sidebar')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
            font-size: 12px;
        }
        
        .container {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .dropdown-container {
            display: flex;
            gap: 20px;
            align-items: center;
            width: 100%;
            
        }
        
        .dropdown-container > * {
            flex: 1;
        }
        
        select, .search-bar input {
            width: 100%;
            font-size: 12px;
            padding: 12px 12px;
            border: 1px solid #4f52ba;
            border-radius: 5px;
            background-color: #fff;
            transition: border-color 0.3s;
        }

        .no-data {
            text-align: center;
            color: rgba(79, 82, 186, 0.2);
        }
        
        .select2-container {
            width: 100%;
        }
        
        .select2-selection {
            width: 100%;
            font-size: 12px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            transition: border-color 0.3s;
            border: 1px solid #4f52ba !important; /* Apply your custom border color */
        }
        
        .select2-selection:focus {
            border-color: #4f52ba !important; /* Maintain the same border color on focus */
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.5) !important;
        }
        .select2-selection__placeholder {
            color: #4f52ba;
            font-size: 12px;
        }
        
        .select2-container--open .select2-selection {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
        }
        
        .select2-results__option {
            font-size: 12px;
            padding: 10px;
        }
        
        .select2-results__option--highlighted {
            background-color: #4f52ba !important;
            color: #fff;
        }

        .header {
            margin-bottom: 10px;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .card-wrapper {
            /* Pada grid, tiap card-wrapper menjadi satu cell */
        }
        .card-counter {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-end;
            transition: transform 0.2s;
            position: relative;
            overflow: hidden;
            min-height: 140px;
            cursor: pointer;
        }
        .card-counter i {
            position: absolute;
            top: 50%;
            left: 0px;
            transform: translateY(-50%);
            font-size: 125px;
            opacity: 0.2;
            z-index: 1;
        }
        .count-numbers {
            font-size: 35px;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            text-align: right;
        }
        .count-name {
            font-size: 16px;
            position: relative;
            z-index: 2;
            text-align: right;
            font-style: italic;
            opacity: 0.6;
            bottom: 15px;
        }
        .primary {
            background: linear-gradient(45deg, #4f52ba 0%, #6f86e0 100%);
            color: white;
        }
        .toggle-table {
            display: none;
        }

        .toggle-table table {
            width: 100%;
            border-collapse: separate; /* Ubah border-collapse menjadi separate */
            border-radius: 5px;
            overflow: hidden; /* Pastikan border-radius berfungsi */
        }

        .toggle-table tr td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
            border-radius: 4px; /* Tumpulkan border untuk setiap cell */
        }

</style>

<div class="main">
    <div class="container">
        <div class="header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Dashboard</h3>
        </div>

        <div class="dropdown-container">
            <!-- Dropdown Region -->
            <select id="region" name="region[]" multiple>
                <option value="">Pilih Region</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                @endforeach
            </select>

            <!-- Dropdown Site -->
            <select id="site" name="site[]" multiple disabled>
                <option value="">Pilih Site</option>
            </select>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" class="custom-select" placeholder="Cari" />
            </div>
        </div>

        <div class="card-grid">
            @foreach ($racks as $rack)
                <div class="card-wrapper">
                    <!-- Card Rack -->
                    <div class="card-counter primary" data-region="{{ $rack->kode_region }}" data-site="{{ $rack->kode_site }}">
                        <i class="material-symbols-outlined">dataset</i>
                        <span class="count-numbers">Rack {{ $rack->no_rack }}</span>
                        <span class="count-name">{{ $rack->nama_region }}, {{ $rack->nama_site }}</span>
                    </div>
                    <!-- Tabel dengan 2 kolom: kolom pertama berisi nomor urut (dari 42 ke 1) dan kolom kedua berisi kode_pkt (jika nomor slot ada di antara uawal dan uakhir perangkat) -->
                    <div class="toggle-table">
                        <table>
                            <tbody>
                            @for ($i = 1; $i <= 42; $i++)
                                @php
                                    // Supaya nomor 1 berada di paling bawah, hitung dengan 43 - $i
                                    $slotNumber = 43 - $i;
                                    $deviceCode = 'IDLE'; // Default value jika tidak ada perangkat ditemukan
                                    
                                    // Periksa setiap perangkat pada listPerangkat yang memiliki region, site, dan no_rack yang sama dengan rack
                                    foreach($listPerangkat as $device) {
                                        if (
                                            $device->kode_region == $rack->kode_region &&
                                            $device->kode_site == $rack->kode_site &&
                                            $device->no_rack == $rack->no_rack
                                        ) {
                                            // Jika nomor slot termasuk dalam rentang perangkat (dari uawal ke uakhir)
                                            if ($slotNumber >= $device->uawal && $slotNumber <= $device->uakhir) {
                                                // Gabungkan nama_pkt dan type perangkat
                                                $deviceCode = $device->nama_pkt . ' ' . $device->type;
                                                break;
                                            }
                                        }
                                    }
                                    // Jika ditemukan perangkat (bukan IDLE), atur style: background #DCDCF2 dan teks bold
                                    $style = ($deviceCode !== 'IDLE') ? 'background-color: #DCDCF2; font-weight: bold;' : '';
                                @endphp
                                <tr style="{{ $style }}">
                                    <td style="width:20%; vertical-align: bottom;">{{ $slotNumber }}</td>
                                    <td>{{ $deviceCode }}</td>
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk dropdown Region dan Site
    $('#region').select2({
        placeholder: "Pilih Region",
        allowClear: true
    });

    $('#site').select2({
        placeholder: "Pilih Site",
        allowClear: true
    });

    // Saat dropdown Region berubah, ambil Site terkait via AJAX
    $('#region').change(function(){
        const selectedRegions = $(this).val();
        // Reset dropdown Site
        $('#site').prop('disabled', true).empty().append('<option value="">Pilih Site</option>');

        if (selectedRegions && selectedRegions.length > 0) {
            $.get('/get-sites', { regions: selectedRegions }, function(data) {
                $('#site').prop('disabled', false);
                $.each(data, function(key, value) {
                    $('#site').append(new Option(value, key));
                });
            });
        }

        // Lakukan filter untuk kartu rack
        filterRacks();
    });

    // Jika dropdown Site berubah, lakukan filter kembali
    $('#site').change(function(){
        filterRacks();
    });

    // Saat mengetik pada search input, lakukan filter
    $('#searchInput').on('keyup', function(){
        filterRacks();
    });

    // Toggle table pada tiap card ketika diklik
    $('.card-counter').on('click', function() {
        $(this).closest('.card-wrapper').find('.toggle-table').slideToggle();
    });
});

// Fungsi untuk memfilter kartu rack berdasarkan Region, Site, dan search keyword
function filterRacks() {
    var selectedRegions = $('#region').val() || [];
    var selectedSites = $('#site').val() || [];
    var searchKeyword = $('#searchInput').val().toLowerCase();

    $('.card-grid .card-wrapper').each(function() {
        var $card = $(this).find('.card-counter');
        var rackRegion = $card.data('region') ? $card.data('region').toString() : '';
        var rackSite = $card.data('site') ? $card.data('site').toString() : '';

        // Cek kecocokan filter Region
        var regionMatch = (selectedRegions.length === 0) || (selectedRegions.includes(rackRegion));
        // Cek kecocokan filter Site
        var siteMatch = (selectedSites.length === 0) || (selectedSites.includes(rackSite));
        
        // Cek apakah teks pada kartu mengandung kata kunci pencarian
        var cardText = $card.text().toLowerCase();
        var searchMatch = cardText.indexOf(searchKeyword) > -1;

        if (regionMatch && siteMatch && searchMatch) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}
</script>
@endsection
