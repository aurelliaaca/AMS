@extends('layouts.sidebar')
@section('content')
<style>
</style>

<link rel="stylesheet" href="{{ asset('css/general.css') }}">
<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<link rel="stylesheet" href="{{ asset('css/card.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabel.css') }}">

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
            @foreach ($sites as $site)
                @for ($rackNo = 1; $rackNo <= $site->jml_rack; $rackNo++)
                    @php
                        $rackData = $racks->first(function($item) use ($site, $rackNo) {
                            return $item->kode_site == $site->kode_site && $item->no_rack == $rackNo;
                        });
                        
                        if (!$rackData) {
                            $rackData = (object)[
                                'kode_region' => $site->kode_region,
                                'nama_region' => $site->nama_region,
                                'kode_site'   => $site->kode_site,
                                'nama_site'   => $site->nama_site,
                                'no_rack'     => $rackNo,
                            ];
                        }
                        
                        $filledU = 0;
                        foreach ($combinedList as $device) {
                            if (
                                $device->kode_region == $rackData->kode_region &&
                                $device->kode_site == $rackData->kode_site &&
                                $device->no_rack == $rackData->no_rack
                            ) {
                                $filledU += ($device->uakhir - $device->uawal + 1);
                            }
                        }
                        $emptyU = 42 - $filledU;
                    @endphp

                    <div class="card-wrapper">
                        <div class="card-counter primary" data-region="{{ $rackData->kode_region }}" data-site="{{ $rackData->kode_site }}">
                            <canvas id="rackChart{{ $rackData->no_rack }}" width="100" height="100"></canvas>
                            <span class="count-numbers">Rack {{ $rackData->no_rack }}</span>
                            <span class="count-name">{{ $rackData->nama_site }}, {{ $rackData->nama_region }}</span>
                            <span class="count-details">Terisi: {{ $filledU }}U, Kosong: {{ $emptyU }}U</span>
                        </div>
                        <div class="toggle-table">
                            <table>
                                <tbody>
                                    @for ($i = 1; $i <= 42; $i++)
                                        @php
                                            $slotNumber = 43 - $i;
                                            $deviceCode = 'IDLE';
                                            $deviceHostnameParts = [];

                                            foreach ($combinedList as $device) {
                                                if (
                                                    $device->kode_region == $rackData->kode_region &&
                                                    $device->kode_site == $rackData->kode_site &&
                                                    $device->no_rack == $rackData->no_rack
                                                ) {
                                                    if ($slotNumber >= $device->uawal && $slotNumber <= $device->uakhir) {
                                                        $deviceCode = $device->nama_fasilitas ?? ($device->nama_perangkat . ' ' . $device->type);

                                                        if ($device->kode_region)       $deviceHostnameParts[] = $device->kode_region;
                                                        if ($device->kode_site)         $deviceHostnameParts[] = $device->kode_site;
                                                        if ($device->no_rack)           $deviceHostnameParts[] = $device->no_rack;
                                                        if (isset($device->kode_perangkat)) $deviceHostnameParts[] = $device->kode_perangkat;
                                                        if (isset($device->kode_fasilitas)) $deviceHostnameParts[] = $device->kode_fasilitas;
                                                        if (isset($device->fasilitas_ke))   $deviceHostnameParts[] = $device->fasilitas_ke;
                                                        if (isset($device->perangkat_ke))   $deviceHostnameParts[] = $device->perangkat_ke;
                                                        if (isset($device->kode_brand))     $deviceHostnameParts[] = $device->kode_brand;
                                                        if ($device->type)            $deviceHostnameParts[] = $device->type;
                                                        break; 
                                                    }
                                                }
                                            }

                                            $deviceHostname = implode('-', array_filter($deviceHostnameParts));
                                            $style = ($deviceCode !== 'IDLE') ? 'background-color: #DCDCF2; font-weight: bold;' : '';
                                        @endphp
                                        <tr style="{{ $style }}">
                                            <td style="width:10%;">{{ $slotNumber }}</td>
                                            <td style="width:50%;">{{ $deviceHostname }}</td>
                                            <td style="width:30%;">{{ $deviceCode }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endfor
            @endforeach
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        $('#region').select2({
            placeholder: "Pilih Region",
            allowClear: true
        });

        $('#site').select2({
            placeholder: "Pilih Site",
            allowClear: true
        });

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

        $('#site').change(function(){
            filterRacks();
        });

        $('#searchInput').on('keyup', function(){
            filterRacks();
        });

        $('.card-counter').on('click', function() {
            $(this).closest('.card-wrapper').find('.toggle-table').slideToggle();
        });
    });

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
    const combinedList = @json($combinedList);

    document.querySelectorAll('canvas[id^="rackChart"]').forEach(canvas => {
        const rackNo = canvas.id.replace('rackChart', '');

        const parentCard = canvas.closest('.card-counter');
        const rackRegion = parentCard.getAttribute('data-region');
        const rackSite = parentCard.getAttribute('data-site');

        let filledU = 0;

        combinedList.forEach(device => {
            if (
                parseInt(device.no_rack) === parseInt(rackNo) &&
                device.kode_region === rackRegion &&
                device.kode_site === rackSite
            ) {
                filledU += device.uakhir - device.uawal + 1;
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