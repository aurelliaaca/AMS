@extends('layouts.sidebar')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


<link rel="stylesheet" href="{{ asset('css/general.css') }}">
<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<link rel="stylesheet" href="{{ asset('css/card.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabel.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/three-dots@0.3.2/dist/three-dots.min.css">

<div class="main">
    <div class="container">
        <div class="header">
            <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">
                Rack | Jumlah Rack {{ $racks->count() }}
            </h3>
            <button class="add-button" onclick="openAddRackModal()">Tambah Rack</button>
        </div>
            
            <!-- Filter section -->
            <div class="filter-container">
                <select id="region-filter" name="region[]" multiple class="select2">
                    @foreach($regions as $region)
                        <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                    @endforeach
                </select>

                <select id="site-filter" name="site[]" multiple class="select2" disabled>
                </select>

                <div class="search-bar">
                    <input type="text" id="searchInput" class="custom-select" placeholder="Cari" />
                </div>
            </div>

        <!-- Loading overlay -->
        <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; z-index: 9999;">
            <div class="dot-spin"></div>
        </div>

        <!-- Content container -->
        <div id="racks-container">
            <!-- Rack data will be loaded here -->
        </div>
    </div>
</div>

<style>
.dot-spin {
    transform: scale(2);
    animation: dot-spin-animation 1.5s infinite;
}

@keyframes dot-spin-animation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

</style>

<script>
// Store charts to destroy them when reloading data
let pieCharts = {};

// Function to toggle table visibility
function toggleTable(tableId) {
    const table = document.getElementById(tableId);
    
    if (table.style.display === 'block') {
        table.style.display = 'none';
    } else {
        table.style.display = 'block';
    }
}

// Function to create pie chart
function createPieChart(elementId, filledU, emptyU) {
    // Destroy existing chart if it exists
    if (pieCharts[elementId]) {
        pieCharts[elementId].destroy();
    }
    
    const ctx = document.getElementById(elementId).getContext('2d');
    pieCharts[elementId] = new Chart(ctx, {
        type: 'pie',
        plugins: [ChartDataLabels],
        data: {
            labels: ['Terisi', 'Tersedia'],
            datasets: [{
                data: [filledU, emptyU],
                backgroundColor: [
                    '#181D5C',  // Warna untuk Terisi
                    '#32398E'   // Warna untuk Tersedia
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        const label = ctx.chart.data.labels[ctx.dataIndex];
                        const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        
                        if (value === 0) return '';
                        
                        if (value === total) {
                            return `${label}\n${value}U`;
                        }
                        
                        return `${label}\n${value}U`;
                    },
                    color: '#fff',
                    font: {
                        size: 11,
                        weight: 'bold'
                    },
                    textAlign: 'center',
                    padding: 6
                }
            },
            events: []
        }
    });
    
    return pieCharts[elementId];
}

// Function to load rack data
function loadRacks() {
    const loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.style.display = 'flex'; // Tampilkan overlay loading

    const selectedRegions = $('#region-filter').val() || [];
    const selectedSites = $('#site-filter').val() || [];
    const searchKeyword = $('#searchInput').val().toLowerCase();
    const racksContainer = document.getElementById('racks-container');

    fetch('{{ route("load.racks") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            regions: selectedRegions,
            sites: selectedSites,
            search: searchKeyword
        })
    })
    .then(response => response.json())
    .then(data => {
        // Generate HTML for racks
        let regionsHtml = '';
        
        // Filter racks based on selected regions and sites
        const filteredRacks = data.racks.filter(rack => {
            const regionMatch = selectedRegions.length === 0 || selectedRegions.includes(rack.kode_region);
            const siteMatch = selectedSites.length === 0 || selectedSites.includes(rack.kode_site);
            const searchMatch = !searchKeyword || 
                rack.no_rack.toString().toLowerCase().includes(searchKeyword) ||
                rack.site.nama_site.toLowerCase().includes(searchKeyword) ||
                rack.region.nama_region.toLowerCase().includes(searchKeyword);
            
            return regionMatch && siteMatch && searchMatch;
        });

        // Group filtered racks by region
        const racksByRegion = filteredRacks.reduce((acc, rack) => {
            const regionCode = rack.kode_region;
            if (!acc[regionCode]) {
                acc[regionCode] = [];
            }
            acc[regionCode].push(rack);
            return acc;
        }, {});
        
        // Generate HTML for each region
        Object.keys(racksByRegion).forEach(regionCode => {
            const racks = racksByRegion[regionCode];
            const regionName = racks[0].region.nama_region;
            
            regionsHtml += `
            <div class="region-wrapper">
                <h4 class="region-title">${regionName} | Jumlah Rack: ${racks.length}</h4>
                <div class="card-grid" style="margin-top: 10px">
            `;
            
            // Generate HTML for each rack in the region
            racks.forEach(rack => {
                const chartId = `pieChart-${rack.kode_region}-${rack.kode_site}-${rack.no_rack}`;
                const tableId = `table-${rack.kode_region}-${rack.kode_site}-${rack.no_rack}`;
                
                regionsHtml += `
                    <div class="card-wrapper">
                        <div class="card-counter primary clickable" onclick="toggleTable('${tableId}')">
                            <div class="icon-wrapper-chart">
                                <canvas id="${chartId}" style="width: 150px; height: 150px;"></canvas>
                            </div>
                            <div class="card-info">
                                <div class="count-numbers">Rack ${rack.no_rack}</div>
                                <div class="count-name">${rack.site.nama_site}, ${rack.region.nama_region}</div>
                                <div class="count-details">Jumlah Perangkat: ${rack.device_count} | Jumlah Fasilitas: ${rack.facility_count}</div>
                            </div>
                        </div>
                        
                        <div id="${tableId}" class="toggle-table">
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID U</th>
                                            <th>ID Perangkat/Fasilitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;
                
                // Generate table rows for rack details
                rack.details.forEach(detail => {
                    let deviceInfo = 'IDLE';
                    
                    if (detail.listperangkat) {
                        let deviceCode = [
                            detail.listperangkat.kode_region,
                            detail.listperangkat.kode_site,
                            detail.listperangkat.no_rack,
                            detail.listperangkat.kode_perangkat,
                            detail.listperangkat.perangkat_ke,
                            detail.listperangkat.kode_brand,
                            detail.listperangkat.type
                        ].filter(Boolean).join('-');
                        
                        deviceInfo = deviceCode;
                    } else if (detail.listfasilitas) {
                        deviceInfo = detail.listfasilitas.nama_fasilitas;
                    }
                    
                    regionsHtml += `
                        <tr>
                            <td>${detail.u}</td>
                            <td>${deviceInfo}</td>
                        </tr>
                    `;
                });
                
                regionsHtml += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            regionsHtml += `
                    </div>
                </div>
            `;
        });
        
        racksContainer.innerHTML = regionsHtml;
        
        // Reinitialize charts
        data.racks.forEach(rack => {
            const chartId = `pieChart-${rack.kode_region}-${rack.kode_site}-${rack.no_rack}`;
            setTimeout(() => {
                createPieChart(chartId, rack.filled_u, rack.empty_u);
            }, 0);
        });
        
        loadingOverlay.style.display = 'none'; // Hentikan loading overlay setelah data selesai dimuat
    })
    .catch(error => {
        console.error('Error loading rack data:', error);
        loadingOverlay.style.display = 'none';
        
        racksContainer.innerHTML = `
            <div class="error-message" style="text-align: center; padding: 20px;">
                <i class="fas fa-exclamation-triangle" style="color: #ff6b6b; font-size: 24px;"></i>
                <p style="color: #ff6b6b; margin-top: 10px;">Failed to load rack data. Please try again later.</p>
            </div>
        `;
    });
}


$(document).ready(function() {
    // Initialize Select2
    $('#region-filter').select2({
        placeholder: "Pilih Region",
        allowClear: true
    });

    $('#site-filter').select2({
        placeholder: "Pilih Site",
        allowClear: true
    });

    // Handle region filter change
    $('#region-filter').on('change', function() {
        const selectedRegions = $(this).val();
        $('#site-filter').prop('disabled', true).empty().append('<option value="">Pilih Site</option>');

        if (selectedRegions && selectedRegions.length > 0) {
            $.get('/get-sites', { regions: selectedRegions }, function(data) {
                $('#site-filter').prop('disabled', false);
                $.each(data, function(key, value) {
                    $('#site-filter').append(new Option(value, key));
                });
            });
        } else {
            // If no regions selected, load all sites
            $.get('/get-sites', function(data) {
                $('#site-filter').prop('disabled', false);
                $.each(data, function(key, value) {
                    $('#site-filter').append(new Option(value, key));
                });
            });
        }
        loadRacks();
    });

    // Handle site filter change
    $('#site-filter').on('change', function() {
        loadRacks();
    });

    // Handle search input with debounce
    let searchTimeout;
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadRacks();
        }, 300);
    });

    // Initial load
    loadRacks();
});
</script>
@endsection