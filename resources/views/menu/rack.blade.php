@extends('layouts.sidebar')
@section('content')
<style>
    /* Tambahkan style custom jika diperlukan */
</style>

<!-- Jangan lupa untuk memindahkan elemen <head> ke layout utama jika memungkinkan -->
<link rel="stylesheet" href="{{ asset('css/aset.css') }}">

<div class="main">
    <div class="container">
    <div class="header">
        <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">
            Rack | Jumlah rack yang terisi: {{ $racks->count() }}, kosong {{ $totalRacks-$racks->count() }}
        </h3>
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
                @php
                    $filledU = 0;
                    foreach($listPerangkat as $device) {
                        if ($device->kode_region == $rack->kode_region &&
                            $device->kode_site == $rack->kode_site &&
                            $device->no_rack == $rack->no_rack) {
                            $filledU += ($device->uakhir - $device->uawal + 1);
                        }
                    }
                    $emptyU = 42 - $filledU;
                @endphp
                <div class="card-wrapper">
                    <div class="card-counter primary" data-region="{{ $rack->kode_region }}" data-site="{{ $rack->kode_site }}">
                        <canvas id="rackChart{{ $rack->no_rack }}" width="100" height="100"></canvas>
                        <span class="count-numbers">Rack {{ $rack->no_rack }}</span>
                        <span class="count-name">{{ $rack->nama_site }}, {{ $rack->nama_region }}</span>
                        <span class="count-details">Terisi: {{ $filledU }}U, Kosong: {{ $emptyU }}U</span>
                    </div>
                    <!-- Tabel dengan 2 kolom: nomor slot dan kode perangkat -->
                    <div class="toggle-table">
                        <table>
                            <tbody>
                            @for ($i = 1; $i <= 42; $i++)
                                @php
                                    // Nomor slot dari 42 ke 1
                                    $slotNumber = 43 - $i;
                                    $deviceCode = 'IDLE'; // Nilai default jika tidak ada perangkat
                                    
                                    // Cek setiap perangkat yang sesuai dengan region, site, dan rack
                                    foreach($listPerangkat as $device) {
                                        if (
                                            $device->kode_region == $rack->kode_region &&
                                            $device->kode_site == $rack->kode_site &&
                                            $device->no_rack == $rack->no_rack
                                        ) {
                                            // Jika slot berada dalam rentang perangkat
                                            if ($slotNumber >= $device->uawal && $slotNumber <= $device->uakhir) {
                                                $deviceCode = $device->nama_perangkat . ' ' . $device->type;
                                                break;
                                            }
                                        }
                                    }
                                    // Style khusus jika slot terisi
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

<!-- Library CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        // Saat dropdown Region berubah, ambil data Site via AJAX
        $('#region').change(function(){
            const selectedRegions = $(this).val();
            $('#site').prop('disabled', true).empty().append('<option value="">Pilih Site</option>');

            if (selectedRegions && selectedRegions.length > 0) {
                $.get('/get-sites', { regions: selectedRegions }, function(data) {
                    $('#site').prop('disabled', false);
                    $.each(data, function(key, value) {
                        $('#site').append(new Option(value, key));
                    });
                });
            }
            filterRacks();
        });

        // Saat dropdown Site berubah, lakukan filter
        $('#site').change(function(){
            filterRacks();
        });

        // Saat mengetik pada search input, lakukan filter
        $('#searchInput').on('keyup', function(){
            filterRacks();
        });

        // Toggle tampilan tabel pada tiap card saat diklik
        $('.card-counter').on('click', function() {
            $(this).closest('.card-wrapper').find('.toggle-table').slideToggle();
        });
    });

    // Fungsi filter untuk menampilkan rak sesuai pilihan region, site, dan kata kunci
    function filterRacks() {
        var selectedRegions = $('#region').val() || [];
        var selectedSites = $('#site').val() || [];
        var searchKeyword = $('#searchInput').val().toLowerCase();

        $('.card-grid .card-wrapper').each(function() {
            var $card = $(this).find('.card-counter');
            var rackRegion = $card.data('region') ? $card.data('region').toString() : '';
            var rackSite = $card.data('site') ? $card.data('site').toString() : '';
            var cardText = $card.text().toLowerCase();

            var regionMatch = (selectedRegions.length === 0) || (selectedRegions.includes(rackRegion));
            var siteMatch = (selectedSites.length === 0) || (selectedSites.includes(rackSite));
            var searchMatch = cardText.indexOf(searchKeyword) > -1;

            if (regionMatch && siteMatch && searchMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
    const totalU = 42;
    const listPerangkat = @json($listPerangkat);

    document.querySelectorAll('canvas[id^="rackChart"]').forEach(canvas => {
        const rackNo = canvas.id.replace('rackChart', '');

        const parentCard = canvas.closest('.card-counter');
        const rackRegion = parentCard.getAttribute('data-region');
        const rackSite = parentCard.getAttribute('data-site');

        let filledU = 0;

        listPerangkat.forEach(perangkat => {
            if (
                parseInt(perangkat.no_rack) === parseInt(rackNo) &&
                perangkat.kode_region === rackRegion &&
                perangkat.kode_site === rackSite
            ) {
                filledU += perangkat.uakhir - perangkat.uawal + 1;
            }
        });

        const emptyU = totalU - filledU;

        new Chart(canvas.getContext("2d"), {
            type: 'pie',
            data: {
                labels: ['Terisi', 'Kosong'],
                datasets: [{
                    data: [filledU, emptyU],
                    backgroundColor: ['#4CAF50', '#FFC107']
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
});

</script>
@endsection