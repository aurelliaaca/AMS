@extends('layouts.sidebar')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/data.css') }}">
<link rel="stylesheet" href="{{ asset('css/general.css') }}">
<link rel="stylesheet" href="{{ asset('css/card.css') }}">
<link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .fa-city {
        font-size: 24px;
        color: #4f52ba;
    }
</style>
<div class="main">
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Region</h3>
                <button type="button" class="add-button" onclick="openAddRegionModal()">Tambah Region</button>
            </div>
        </div>
        <div class="card-grid" id="regionCardsContainer">
            <!-- Cards will be loaded here dynamically -->
        </div>
    </div>
</div>

@include('data.add-region')
@include('data.edit-region')

<script>
    $(document).ready(function() {
        // Load data when page first opens
        loadData();
    });

    function toggleTable(regionCode) {
        const table = document.getElementById('table-' + regionCode);
        table.style.display = (table.style.display === 'block') ? 'none' : 'block';
    }

    function openAddRegionModal() {
        $('#addRegionForm')[0].reset();
        document.getElementById("addRegionModal").style.display = "flex";
    }

    function closeAddRegionModal() {
        document.getElementById("addRegionModal").style.display = "none";
    }

    function loadData() {
        $.ajax({
            url: '/get-regions',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    response.regions.forEach(function(region) {
                        html += `
                        <div class="card-wrapper">
                            <div class="card-counter" data-region="${region.kode_region}" onclick="toggleTable('${region.kode_region}')">
                                <div class="icon-wrapper">
                                    <i class="fa-solid fa-city"></i>
                                </div>
                                <div class="card-info">
                                    <div class="count-numbers">${region.nama_region || ''}</div>
                                    <div class="count-name">Jumlah POP: ${region.jumlah_pop || 0} | Jumlah POC: ${region.jumlah_poc || 0}</div>
                                    <div class="count-details">${region.email || ''}</div>
                                </div>
                                <div class="action-buttons">
                                    <button onclick="event.stopPropagation(); openAddSiteModal(${region.id_region})"
                                        style="background-color:rgb(209, 210, 241); color: white; border: none; padding: 5px; border-radius: 3px; cursor: pointer; margin-right: 5px; margin-right: -1px;">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); lihatRegion(${region.id_region})"
                                        style="background-color: #9697D6; color: white; border: none; padding: 5px; border-radius: 3px; cursor: pointer; margin-right: 5px; margin-right: -1px;">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); editRegion(${region.id_region})"
                                        style="background-color: #4f52ba; color: white; border: none; padding: 5px; border-radius: 3px; margin-right: 5px; cursor: pointer; margin-right: -1px; margin-left: -1px;">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); deleteRegion(${region.id_region})"
                                        style="background-color: #dc3545; color: white; border: none; padding: 5px; border-radius: 3px; cursor: pointer; margin-left: -1px;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="toggle-table" id="table-${region.kode_region}">
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Site</th>
                                                <th>Kode Site</th>
                                                <th>Jenis Site</th>
                                                <th>Jumlah Rack</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                            if (region.sites && region.sites.length > 0) {
                                                region.sites.forEach(function(site) {
                                                    html += `
                                                                <tr>
                                                                    <td>${site.nama_site || ''}</td>
                                                                    <td>${site.kode_site || ''}</td>
                                                                    <td>${site.jenis_site || ''}</td>
                                                                    <td>${site.jml_rack || ''}</td>
                                                                    <td>
                                                                        <div class="action-buttons">
                                                                            <button onclick="event.stopPropagation(); editSite(${site.id_site})"
                                                                                style="background-color: #4f52ba; color: white; border: none; padding: 5px; border-radius: 3px; margin-right: 5px; cursor: pointer; margin-right: -2px;">
                                                                                <i class="fa-solid fa-pen"></i>
                                                                            </button>
                                                                            <button onclick="deleteSite(${site.id_site})"
                                                                                style="background-color: #dc3545; color: white; border: none; padding: 5px; border-radius: 3px; cursor: pointer; margin-left: -1px;">
                                                                                <i class="fa-solid fa-trash-can"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>`;
                                                });
                                            } else {
                                                html += `
                                                                <tr>
                                                                    <td colspan="5" class="no-data">Belum ada data site</td>
                                                                </tr>`;
                                            }
                                            html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>`;
                    });
                    $('#regionCardsContainer').html(html);
                }
                                    // Debug untuk melihat data yang diterima
                                    console.log('Data regions:', response.regions);
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Gagal memuat data region', 'error');
            }
        });
    }

    function lihatRegion(idRegion) {
        // Prevent event bubbling
        event.stopPropagation(); 
        console.log('Lihat region:', idRegion);
        // Implement view region functionality
    }

    function deleteRegion(idRegion) {
        event.stopPropagation(); // Prevent card toggle
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/region/delete/${idRegion}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            ).then(() => {
                                loadData();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menghapus data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    }

    function deleteSite(idSite) {
        event.stopPropagation(); // Prevent card toggle
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/site/delete/${idSite}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            ).then(() => {
                                loadData();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menghapus data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection