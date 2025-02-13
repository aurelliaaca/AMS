@extends('layouts.sidebar')
@section('content')
<style>
</style>

    <head>
        <link rel="stylesheet" href="{{ asset('css/aset.css') }}">
    </head>

<div class="main">
    <div class="container">
        <div class="header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Dashboard</h3>
        </div>

        <div class="filter-container">
            <select id="region" name="region[]" multiple>
                <option value="">Pilih Region</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                @endforeach
            </select>

            <select id="site" name="site[]" multiple disabled>
                <option value="">Pilih Site</option>
            </select>

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
