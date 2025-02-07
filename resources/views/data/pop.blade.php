@extends('layouts.sidebar')

@section('content')
    <style>
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
        .tables-wrapper {
            display: flex;
            gap: 20px;
        }

        .table-container h3 {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th:first-child {
            border-top-left-radius: 8px;
        }
        
        th:last-child {
            border-top-right-radius: 8px;
        }
        
        tr:last-child td:first-child {
            border-bottom-left-radius: 8px;
        }
        
        tr:last-child td:last-child {
            border-bottom-right-radius: 8px;
        }
        
        th {
            background-color: #4f52ba;
            color: #fff;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-weight: normal;
            font-size: 12px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .no-data {
            text-align: center;
            color: rgba(79, 82, 186, 0.2);
        }

        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin: 0 2px;
        }

        .btn-edit {
            background-color: #4f52ba;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-edit:hover {
            background-color: #3a3d9c;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .add-button {
            background-color: #4f52ba;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 18.75%;
        }

        .add-button:hover {
            background-color: #3a3d9c;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 500px;
            position: relative;
        }

        .modal-content h2 {
            color: #595959;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #595959;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-weight: normal;
            background-color: #fff;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.3);
            outline: none;
        }

        .modal-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .modal-close-btn:hover {
            color: #333;
        }

        .button-container {
            display: flex;
            justify-content: right;
            gap: 10px;
            margin-top: 20px;
        }
    </style>

    <div class="main">
        <div class="container">
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data POP</h3>
                    <button type="button" class="add-button" onclick="openAddPOPModal()">Tambah POP</button>
                </div>
            </div>

            <!-- Table Data POP -->
            <div class="table-container">
                <table id="tablePOP">
                    <thead>
                        <tr>
                            <th>No Site</th>
                            <th>Regional</th>
                            <th>Kode Regional</th>
                            <th>Jenis Site</th>
                            <th>Site</th>
                            <th>Kode</th>
                            <th>Keterangan</th>
                            <th>Wajib Inspeksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pop as $index => $pop)
                            <tr>
                                <td>${index + 1}</td>
                                <td>${pop.nama_region}</td>
                                <td>${pop.kode_regional || '-'}</td>
                                <td>${pop.jenis_site || '-'}</td>
                                <td>${pop.site || '-'}</td>
                                <td>${pop.kode || '-'}</td>
                                <td>${pop.keterangan || '-'}</td>
                                <td>${pop.wajib_inspeksi ? 'Ya' : 'Tidak'}</td>
                                <td>
                                    <button type="button" class="btn-edit" onclick="editPOP({{ $pop->no_site }})">Edit</button>
                                    <button type="button" class="btn-delete" onclick="deletePOP({{ $pop->no_site }})">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="no-data">Tidak ada data POP</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="addPOPModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <button class="modal-close-btn" onclick="closeAddPOPModal()">×</button>
            <h2>Tambah POP Baru</h2>
            <form id="addPOPForm">
                @csrf
                <div class="form-group">
                    <label for="regional">Regional</label>
                    <input type="text" id="regional" name="nama_region" required>
                </div>
                <div class="form-group">
                    <label for="kode_regional">Kode Regional</label>
                    <input type="text" id="kode_regional" name="kode_regional" required>
                </div>
                <div class="form-group">
                    <label for="jenis_site">Jenis Site</label>
                    <input type="text" id="jenis_site" name="jenis_site" required>
                </div>
                <div class="form-group">
                    <label for="site">Site</label>
                    <input type="text" id="site" name="site" required>
                </div>
                <div class="form-group">
                    <label for="kode">Kode</label>
                    <input type="text" id="kode" name="kode" required>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="wajib_inspeksi">Wajib Inspeksi</label>
                    <select id="wajib_inspeksi" name="wajib_inspeksi" required>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <div class="button-container">
                    <button type="submit" class="add-button">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            LoadData();

            // ------------------------- STORE/UPDATE DATA -------------------------
            $('#addPOPForm').submit(function(e) {
                e.preventDefault();
                
                const no_site = $('#no_site-input').val();
                const url = no_site ? `/update-pop/${no_site}` : '/store-pop';
                const method = no_site ? 'PUT' : 'POST';
                
                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            closeAddPOPModal();
                            showSwal('success', no_site ? 'POP berhasil diupdate!' : 'POP berhasil ditambahkan!');
                            LoadData();
                            $('#addPOPForm')[0].reset();
                            $('#no_site-input').remove();
                            $('.add-button[type="submit"]').text('Simpan');
                        } else {
                            showSwal('error', response.message || 'Terjadi kesalahan');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        showSwal('error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            // ----------------------- GLOBAL MODAL FUNCTIONS -----------------------
            window.openAddPOPModal = function() {
                $('#addPOPModal').css('display', 'flex');
            }

            window.closeAddPOPModal = function() {
                $('#addPOPModal').css('display', 'none');
            }

            // ----------------------- ALERT -----------------------
            function showSwal(type, message) {
                swal({
                    title: type === 'success' ? "Berhasil!" : "Error!",
                    text: message,
                    type: type,
                    button: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: type === 'success' ? "btn btn-primary" : "btn btn-danger"
                    }
                });
            }

            // ----------------------- FUNCTION LOAD DATA -----------------------
            function LoadData() {
                $.get('/get-pop', function(response) {
                    const tbody = $('#tablePOP tbody');
                    tbody.empty();

                    if (response.pop && response.pop.length > 0) {
                        $.each(response.pop, function(index, pop) {
                            tbody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${pop.nama_region}</td>
                                    <td>${pop.kode_regional || '-'}</td>
                                    <td>${pop.jenis_site || '-'}</td>
                                    <td>${pop.site || '-'}</td>
                                    <td>${pop.kode || '-'}</td>
                                    <td>${pop.keterangan || '-'}</td>
                                    <td>${pop.wajib_inspeksi ? 'Ya' : 'Tidak'}</td>
                                    <td>
                                        <button onclick="editPOP(${pop.id})" 
                                        style="background-color: #4f52ba; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;"
                                        class="edit-btn">Edit</button>
                                        <button onclick="deletePOP(${pop.id})" 
                                        style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;"
                                        class="delete-btn">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="11" style="text-align: center;">Tidak ada data POP</td></tr>');
                    }
                }).fail(function(xhr) {
                    console.error('LoadData Error:', xhr.responseText);
                    const tbody = $('#tablePOP tbody');
                    tbody.empty().append('<tr><td colspan="11" style="text-align: center;">Terjadi kesalahan dalam memuat data</td></tr>');
                });
            }

            // ----------------------- FUNCTION EDIT -----------------------
            window.editPOP = function(no_site) {
                $.get(`/get-pop/${no_site}`, function(response) {
                    if (response.success) {
                        const pop = response.pop;
                        
                        $('#addPOPForm')[0].reset();
                        $('#nama_region').val(pop.nama_region);
                        $('#kode_regional').val(pop.kode_regional);
                        $('#jenis_site').val(pop.jenis_site);
                        $('#site').val(pop.site);
                        $('#kode').val(pop.kode);
                        $('#keterangan').val(pop.keterangan);
                        $('#wajib_inspeksi').val(pop.wajib_inspeksi ? '1' : '0');
                        
                        $('#no_site-input').remove();
                        $('#addPOPForm').append(`<input type="hidden" id="no_site-input" name="no_site" value="${pop.no_site}">`);
                        
                        $('h2').text('Edit POP');
                        $('.add-button[type="submit"]').text('Update');
                        
                        openAddPOPModal();
                    }
                }).fail(function() {
                    showSwal('error', 'Gagal mengambil data POP');
                });
            }

            // ----------------------- FUNCTION DELETE -----------------------
            window.deletePOP = function(no_site) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data POP akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal",
                    closeOnConfirm: false
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: `/delete-pop/${no_site}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    showSwal('success', 'POP berhasil dihapus');
                                    LoadData();
                                } else {
                                    showSwal('error', response.message || 'Gagal menghapus POP');
                                }
                            },
                            error: function() {
                                showSwal('error', 'Gagal menghapus POP');
                            }
                        });
                    }
                });
            }

            // -------------------- TUTUP MODAL KETIKA KLIK DI LUAR --------------------
            window.onclick = function(event) {
                const modal = document.getElementById("addPOPModal");
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            };
        });
    </script>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">


