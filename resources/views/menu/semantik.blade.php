@extends('layouts.sidebar')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
    <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
    <style>
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.7); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* 10% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            border-radius: 8px; /* Rounded corners */
            width: 90%; /* Could be more or less, depending on screen size */
            max-width: 600px; /* Maximum width for larger screens */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        }

        .modal-content img {
            width: 100%; /* Gambar akan mengambil lebar penuh dari modal */
            height: auto; /* Menjaga rasio aspek gambar */
            max-height: 80vh; /* Maksimal tinggi gambar */
            object-fit: contain; /* Menjaga rasio aspek gambar */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .button {
            background-color: #4f52ba; /* Warna latar belakang tombol */
            color: white; /* Warna teks tombol */
            border: none; /* Tanpa border */
            padding: 10px 15px; /* Padding tombol */
            cursor: pointer; /* Kursor pointer saat hover */
            border-radius: 5px; /* Sudut melengkung */
            transition: background-color 0.3s; /* Transisi saat hover */
        }

        .button:hover {
            background-color: #3e4a9a; /* Warna latar belakang saat hover */
        }

        .delete-button {
            background-color: #dc3545; /* Warna latar belakang tombol hapus */
            color: white; /* Warna teks tombol hapus */
        }

        .delete-button:hover {
            background-color: #c82333; /* Warna latar belakang saat hover untuk tombol hapus */
        }

        /* CSS untuk modal upload foto */
        #uploadModal .modal-content {
            background-color: #fefefe; /* Warna latar belakang modal */
            margin: 10% auto; /* 10% dari atas dan terpusat */
            padding: 20px; /* Padding di dalam modal */
            border: 1px solid #888; /* Border modal */
            border-radius: 8px; /* Sudut melengkung */
            width: 90%; /* Lebar modal */
            max-width: 500px; /* Lebar maksimum untuk layar besar */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Bayangan untuk kedalaman */
        }

        /* Gaya untuk tombol upload di dalam modal */
        #uploadModal .button {
            background-color: #4f52ba; /* Warna latar belakang tombol */
            color: white; /* Warna teks tombol */
            border: none; /* Tanpa border */
            padding: 10px 15px; /* Padding tombol */
            cursor: pointer; /* Kursor pointer saat hover */
            border-radius: 5px; /* Sudut melengkung */
            transition: background-color 0.3s; /* Transisi saat hover */
        }

        #uploadModal .button:hover {
            background-color: #3e4a9a; /* Warna latar belakang saat hover */
        }

        /* Gaya untuk label dan input di dalam modal */
        #uploadModal label {
            font-weight: bold; /* Tebal untuk label */
            margin-top: 10px; /* Jarak atas untuk label */
        }

        #uploadModal input[type="text"],
        #uploadModal textarea,
        #uploadModal input[type="file"] {
            width: 100%; /* Lebar penuh untuk input */
            padding: 10px; /* Padding di dalam input */
            margin-top: 5px; /* Jarak atas untuk input */
            border: 1px solid #ccc; /* Border input */
            border-radius: 4px; /* Sudut melengkung */
        }

        /* Gaya untuk textarea */
        #uploadModal textarea {
            resize: vertical; /* Memungkinkan resize vertikal */
        }

        /* Gaya untuk card foto */
        .card {
            background-color: #fff; /* Warna latar belakang card */
            border: 1px solid #ddd; /* Border card */
            border-radius: 8px; /* Sudut melengkung */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bayangan untuk kedalaman */
            overflow: hidden; /* Menghindari overflow konten */
            transition: transform 0.2s; /* Transisi saat hover */
        }

        /* Efek hover untuk card */
        .card:hover {
            transform: scale(1.02); /* Membesarkan card sedikit saat hover */
        }

        /* Gaya untuk gambar di dalam card */
        .card img {
            width: 100%; /* Gambar mengambil lebar penuh dari card */
            height: 150px; /* Tinggi tetap untuk gambar */
            object-fit: cover; /* Memastikan gambar tidak terdistorsi */
        }

        /* Gaya untuk body card */
        .card-body {
            padding: 15px; /* Padding di dalam body card */
        }

        /* Gaya untuk footer card */
        .card-footer {
            background-color: #f7f7f7; /* Warna latar belakang footer */
            padding: 10px; /* Padding di dalam footer */
            display: flex; /* Menggunakan flexbox untuk footer */
            justify-content: space-between; /* Menyebar konten di footer */
            align-items: center; /* Menyelaraskan item di tengah */
        }

        /* Gaya untuk teks di dalam footer */
        .card-footer small {
            color: #6c757d; /* Warna teks untuk footer */
        }

        .modal-title {
            text-align: center; /* Mengatur teks agar berada di tengah */
            font-size: 24px; /* Ukuran font yang lebih besar */
            font-weight: bold; /* Membuat teks menjadi tebal */
            margin-bottom: 15px; /* Jarak bawah untuk memberi ruang */
        }
    </style>
</head>

<div class="main">
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="color: #4f52ba; font-size: 20px;">Semantik Jaringan</h1>
                <button class="button" style="margin-left: auto;" onclick="showModal('uploadModal')">
                    Tambah Foto
                </button>
            </div>

        <div id="uploadModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeModal('uploadModal')">&times;</span>
                <h5 class="modal-title">Upload Foto</h5>
                <form id="uploadPhotoForm" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="photo">Pilih Foto</label>
                        <input type="file" id="photo" name="photo" accept="image/*" required>
                    </div>
                    <div>
                        <label for="title">Judul</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div>
                        <label for="text">Teks</label>
                        <textarea id="text" name="text" rows="3"></textarea>
                    </div>
                    <div style="text-align: right;">
                        <button type="submit" class="button">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="photoGallery" style="display: flex; flex-wrap: wrap;">
            @foreach($photos as $photo)
                <div style="flex: 0 0 calc(50% - 10px); margin: 5px;">
                    <div class="card" style="height: 300px; display: flex; flex-direction: column;">
                        <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title }}" onclick="showImage('{{ asset($photo->file_path) }}', '{{ $photo->title }}')" style="width: 100%; height: 150px; object-fit: cover;">
                        <div class="card-body" style="flex: 1;">
                            <h5>{{ $photo->title }}</h5>
                            <p>{{ $photo->text }}</p>
                        </div>
                        <div class="card-footer" style="display: flex; justify-content: space-between; align-items: center;">
                            <small class="text-muted">Uploaded on: {{ $photo->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}</small>
                            <form action="{{ route('photos.delete', $photo->id) }}" method="POST" style="display:inline;" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="button delete-button" style="background-color: #dc3545;" data-title="{{ $photo->title }}">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="imageModal" class="modal" style="display: none;">
            <div class="modal-content" style="position: relative; z-index: 1000; background: white; padding: 20px; border-radius: 5px; max-width: 80%; margin: auto; top: 50%; transform: translateY(-50%);">
                <span class="close" onclick="closeModal('imageModal')" style="cursor: pointer;">&times;</span>
                <h5 id="modalImageTitle" style="text-align: center;"></h5>
                <img id="modalImage" src="" alt="Foto Besar" style="width: 100%; height: auto; max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('uploadPhotoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/upload-photo', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                closeModal('uploadModal'); // Menutup modal upload
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
        console.log(imageUrl);
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
            const photoTitle = this.getAttribute('data-title');

            Swal.fire({
                title: `Hapus foto "${photoTitle}"?`,
                text: "Tindakan ini tidak bisa dibatalkan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jalankan form penghapusan
                }
            });
        });
    });

    // Menyembunyikan teks di atas modal saat modal dibuka
    function showModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(id) {
    document.getElementById(id).style.display = "none";
    document.querySelectorAll('.header, .card-grid').forEach(element => {
        element.classList.remove('hidden'); // Tampilkan kembali elemen
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

    document.querySelector('.button').addEventListener('click', function() {
        showModal('uploadModal');
    });

    document.querySelectorAll('.card img').forEach(img => {
        img.addEventListener('click', function() {
            showImage(this.src, this.alt);
        });
    });
</script>
@endsection

