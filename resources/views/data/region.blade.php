@extends('layouts.sidebar')
@section('content')

<link rel="stylesheet" href="{{ asset('css/data.css') }}">
<link rel="stylesheet" href="{{ asset('css/general.css') }}">
<link rel="stylesheet" href="{{ asset('css/card.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabel.css') }}">

<div class="main">
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Region</h3>
                <button class="add-button" onclick="openAddRegionModal()">Tambah Region</button>
            </div>
        </div>

        <div class="card-grid">
            @foreach ($regions as $region)
                <div class="card-wrapper">
                    <div class="card-counter primary" data-region="{{ $region->kode_region }}" onclick="toggleTable('{{ $region->kode_region }}')">
                        <i class="material-symbols-outlined">distance</i>
                        <div class="card-info">
                            <div class="count-numbers">{{ $region->nama_region }}, {{ $region->kode_region }} </div>
                            <div class="count-pop">{{ $region->jumlah_pop }}</div>
                            <div class="count-email">{{ $region->email }}</div>
                        </div>
                    </div>
                    <div class="toggle-table" id="table-{{ $region->kode_region }}">
                        <table>
                            <tbody>
                                @foreach($region->sites as $site)
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">{{ $site->nama_site }}</td>
                                    </tr>
                                    @php
                                        $deviceData = \App\Models\Site::where('kode_site', $site->kode_site)->get();
                                        $slotCount = $deviceData->count();
                                    @endphp
                                    @for ($i = 1; $i <= $slotCount; $i++)
                                        @php
                                            $device = $deviceData->where('kode_region', $i)->first();
                                            $deviceCode = $device ? $device->nama_site : '';
                                            $style = $device ? 'background-color: #DCDCF2; font-weight: bold;' : '';
                                        @endphp
                                        @if ($deviceCode)
                                            <tr style="{{ $style }}">
                                                <td style="width:20%; vertical-align: bottom;">{{ $i }}</td>
                                                <td>{{ $deviceCode }}</td>
                                            </tr>
                                        @endif
                                    @endfor
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function toggleTable(regionCode) {
        const table = document.getElementById('table-' + regionCode);
        table.style.display = table.style.display === 'block' ? 'none' : 'block';
    }

    $(document).ready(function() {

    });
</script>
@endsection
