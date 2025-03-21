@extends('layouts.sidebar')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
</head>

<style>
    .main-content {
        position: relative;
        z-index: 5;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .main-content {
        width: 100%;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .button {
        background-color: #4f52ba;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .button:hover {
        background-color: #3e4a9a;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 15px;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #f7f7f7;
    }

    .hidden {
        display: none; /* Sembunyikan elemen */
    }

    .header {
        position: relative; /* Pastikan header memiliki posisi relatif */
        z-index: 1; /* Z-index lebih rendah dari modal */
    }
</style>

<div class="main">
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="color: #4f52ba; font-size: 20px;">Dasbor</h1>
                @if(auth()->user()->role == '1' || auth()->user()->role == '2')
                <!-- Menghapus tombol "Tambah Foto" -->
                <!-- <button class="button" style="margin-left: auto;" onclick="showModal('uploadModal')">Tambah Foto</button> -->
                @endif
                <button class="add-button" onclick="openAddPendaftaranModal()">Daftar</button>
            </div>
        
            <div class="welcome-banner" style="background-color: #f4f5ff; padding: 20px; border-radius: 10px; display: flex; align-items: center; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <img src="{{ asset('img/avatars/1.png') }}" alt="Foto Profil" style="width: 80px; height: 80px; border-radius: 50%; margin-right: 15px; border: 2px solid #4f52ba;">
                <div>
                    <h2 style="color: #4f52ba; margin: 0; font-size: 24px;">Selamat datang, {{ Auth::user()->name }}! 👋</h2>
                    <p style="color: #6c6fba; margin: 5px 0 0; font-size: 16px;">Semoga harimu menyenangkan dan penuh produktivitas!</p>
                </div>
            </div>
        </div>

        @if(auth()->user()->role == '1' || auth()->user()->role == '2')
        <div class="card-grid">
            <div class="card-counter device-icon">
                <i class="fa-solid fa-city"></i>
                <div class="count-numbers">{{ $regionCount }}</div>
                <div class="count-name">Region</div>
            </div>
            <div class="card-counter pop-icon">
                <i class="fa-solid fa-building"></i>
                <div class="count-numbers">{{ $popCount }}</div>
                <div class="count-name">POP</div>
            </div>
            <div class="card-counter facility-icon">
                <i class="fa-solid fa-building-user"></i>
                <div class="count-numbers">{{ $pocCount }}</div>
                <div class="count-name">POC</div>
            </div>
            <div class="card-counter rack-icon">
                <i class="fas fa-server"></i>
                <div class="count-numbers">{{ $totalRacksPOP }}</div>
                <div class="count-name">Rack POP</div>
            </div>
            <div class="card-counter rack-icon">
                <i class="fas fa-server"></i>
                <div class="count-numbers">{{ $totalRacksPOC }}</div>
                <div class="count-name">Rack POC</div>
            </div>
            <div class="card-counter device-icon">
                <i class="fas fa-laptop"></i>
                <div class="count-numbers">{{ $perangkatCount }}</div>
                <div class="count-name">Perangkat</div>
            </div>
            <div class="card-counter facility-icon">
                <i class="fas fa-tools"></i>
                <div class="count-numbers">{{ $fasilitasCount }}</div>
                <div class="count-name">Fasilitas</div>
            </div>
            <div class="card-counter">
                <i class="fas fa-ruler-combined"></i>
                <div class="count-numbers">{{ $alatukurCount }}</div>
                <div class="count-name">Alat Ukur</div>
            </div>
            <div class="card-counter">
                <i class="fas fa-circle-nodes"></i>
                <div class="count-numbers">{{ $jaringanCount }}</div>
                <div class="count-name">Jaringan</div>
            </div>
        </div> 
    </div>
</div>

@include('menu.pendaftaran-vms')

<script>
    document.getElementById('uploadPhotoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/upload-photo', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const photoGallery = document.getElementById('photoGallery');
                const card = `
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img class="card-img-top" src="${data.photoUrl}" alt="Card image cap" style="height: 150px; object-fit: cover;" onclick="showImage('${data.photoUrl}', '${data.title}')">
                            <div class="card-body">
                                <h5 class="card-title">${data.title}</h5>
                                <p class="card-text">${data.text}</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Uploaded on: ${data.timestamp}</small>
                                <form action="/photos/${data.id}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
                photoGallery.insertAdjacentHTML('beforeend', card);
                $('#uploadModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Foto berhasil diupload.',
                });
                  // Tutup modal
                  closeModal('uploadModal'); // Menutup modal upload
                // Reload halaman
                location.reload(); // Reload halaman untuk memperbarui galeri foto
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal mengupload foto: ' + data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan!',
                text: 'Kesalahan saat mengupload foto: ' + error.message,
            });
        });
    });

    function showImage(imageUrl, title) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('modalImageTitle').innerText = title;
        document.getElementById('imageModal').style.display = 'block';
        
        // Sembunyikan elemen yang ingin disembunyikan
        document.querySelectorAll('.header, .card-grid').forEach(element => {
            element.classList.add('hidden'); // Menambahkan kelas hidden
        });
    }

    // Close modal when clicking outside of the modal content
    window.onclick = function(event) {
        if (event.target == document.getElementById('uploadModal') || event.target == document.getElementById('imageModal')) {
            closeModal('uploadModal');
            closeModal('imageModal');
        }
    }

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            const photoTitle = this.getAttribute('data-title'); // Ambil judul foto dari data-title

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus foto: ${photoTitle}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#4f52ba',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Kirim form jika pengguna mengkonfirmasi
                }
            });
        });
    });

    // Menyembunyikan teks di atas modal saat modal dibuka
    function showModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
        document.querySelector('.header').classList.add('hidden'); // Sembunyikan elemen header
    }

    // Menutup modal dan menampilkan kembali teks
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        
        // Tampilkan kembali elemen yang disembunyikan
        document.querySelectorAll('.header, .card-grid').forEach(element => {
            element.classList.remove('hidden'); // Menghapus kelas hidden
        });
    }

    // Event listener untuk tombol modal
    document.querySelectorAll('.modal').forEach(modal => {
        modal.querySelector('.close').addEventListener('click', function() {
            closeModal(modal.id);
        });
    });

    // Menampilkan notifikasi setelah penghapusan
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan',
            text: '{{ session('error') }}',
        });
    @endif
</script>
@endsection

