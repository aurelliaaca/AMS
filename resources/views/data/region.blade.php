@extends('layouts.sidebar')
@section('content')
<style>

    .card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* Change from 4 to 3 columns */
        gap: 20px;
        margin-top: 20px;
    }

    /* Responsive Card Layout */
    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }
/* Card Styling */
.card-counter {
    background: linear-gradient(45deg, #4f52ba 0%, #6f86e0 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    flex-direction: row; /* Horizontal layout */
    justify-content: flex-start; /* Keep items aligned to the start (left) */
    align-items: center; /* Center align items vertically */
    transition: transform 0.2s ease;
    position: relative;
    overflow: hidden;
    min-height: 140px;
    cursor: pointer;
}

.card-counter i {
    font-size: 150px; /* Larger icon size */
    opacity: 0.15; /* Make the icon subtle */
    margin-right: 20px; /* Space between the icon and text */
    z-index: 1;
}

.card-counter > div {
    display: flex;
    flex-direction: column; /* Stack text vertically */
    justify-content: flex-start; /* Align text to the top */
    align-items: flex-end; /* Align text to the right */
    flex-grow: 1; /* Make sure text takes up available space */
}

.card-info {
    display: flex;
    flex-direction: column; /* Mengatur agar informasi ditampilkan dalam kolom */
    align-items: flex-end; /* Mengatur agar semua teks berada di sebelah kanan */
    width: 100%; /* Memastikan lebar penuh */
}

.count-numbers {
    font-size: 16px; /* Ukuran font untuk nama region */
    font-weight: bold; /* Menebalkan teks */
}

.count-pop, .count-email {
    font-size: 14px; /* Ukuran font untuk jumlah pop dan email */
    opacity: 0.8; /* Sedikit transparansi untuk membedakan */
}

.jumlah-pop {
    font-size: 14px;
    font-weight: normal;
    text-align: right; /* Align text to the right */
    color: #fff;
    opacity: 0.8;
    margin-bottom: 5px; /* Reduced margin for consistency */
}

.count-name {
    font-size: 16px;
    position: relative;
    z-index: 2;
    text-align: right; /* Align text to the right */
    font-style: italic;
    opacity: 0.6;
    bottom: 5px;
}


.count-name {
    font-size: 16px;
    position: relative;
    z-index: 2;
    text-align: right; /* Align text to the right */
    font-style: italic;
    opacity: 0.6;
    bottom: 5px;
}


    .toggle-table {
        display: none;
    }

    .toggle-table table {
        width: 100%;
        border-collapse: separate;
        border-radius: 5px;
        overflow: hidden;
    }

    .toggle-table tr td {
        border: 1px solid #ddd;
        padding: 5px;
        text-align: center;
        border-radius: 4px;
    }

    /* Responsive Card Layout */
    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }

    /* Card Styling */
    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    /* Hover Effect on Card */
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    /* Card Header Styling */
    .card-header {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Icon Wrapper */
    .icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Icon Styling */
    .icon-wrapper i {
        font-size: 24px;
        color: white;
    }

    /* -------------------------- FILTER -------------------------- */
    .filter-container {
        display: flex;
        gap: 20px;
        align-items: center;
        width: 100%;
    }

    .filter-container > * {
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

    .search-bar input {
        outline: none;
    }

    select:focus, .search-bar input:focus {
        border-color: #4f52ba;
        box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
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
        border: 1px solid #4f52ba !important;
    }

    .select2-selection:focus {
        border-color: #4f52ba !important;
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
</style>
<div class="main">
    <div class="container">
        <div class="header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Region</h3>
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
    // Function to toggle the visibility of the table
    function toggleTable(regionCode) {
        const table = document.getElementById('table-' + regionCode);
        table.style.display = table.style.display === 'block' ? 'none' : 'block';
    }

    $(document).ready(function() {
        // Initialize Select2 with custom settings
        $('#region').select2({
            placeholder: "Pilih Region",
            allowClear: true,
            closeOnSelect: false,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tidak ada hasil";
                }
            }
        });

        $('#site').select2({
            placeholder: "Pilih Site",
            allowClear: true,
            closeOnSelect: false,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tidak ada hasil";
                }
            }
        });

        // Adjust dropdown width to match container
        $('#region, #site').on('select2:open', function() {
            $('.select2-dropdown').css('width', '300px');
        });

        // When region is selected, fetch sites from the database
        $('#region').change(function() {
            let selectedRegions = $(this).val();

            // Empty the site dropdown
            $('#site').empty().append('<option value="">Pilih Site</option>').prop('disabled', true);

            if (selectedRegions && selectedRegions.length > 0) {
                $.ajax({
                    url: '/get-sites',
                    type: 'POST',
                    data: { 
                        kode_region: selectedRegions, 
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        $('#site').prop('disabled', false);
                        response.forEach(site => {
                            $('#site').append(new Option(site.nama_site, site.kode_site));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching sites:', error);
                    }
                });
            }

            filterRacks(); // Call filter function to update display
        });

        // When site is selected
        $('#site').change(function() {
            filterRacks(); // Call filter function to update display
        });

        // Search functionality
        $('#searchInput').on('keyup', function() {
            filterRacks(); // Call filter function to update display
        });
    });
</script>
@endsection
