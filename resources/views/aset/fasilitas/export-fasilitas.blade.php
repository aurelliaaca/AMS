<!DOCTYPE html>
<html>
<head>
    <title>Data Fasilitas</title>
    <style>
        @page {
            size: A4 landscape; /* Mengatur ukuran halaman menjadi landscape */
            margin: 20mm; /* Mengatur margin halaman */
        }
        body {
            font-family: Arial, sans-serif;
            background-color: white; /* Pastikan latar belakang putih */
            color: black; /* Pastikan teks berwarna hitam */
            position: relative; /* Menambahkan posisi relatif untuk body */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 4px; /* Mengurangi padding untuk menghemat ruang */
            text-align: left;
            font-size: 10px; /* Mengurangi ukuran font untuk menghemat ruang */
            word-wrap: break-word; /* Memungkinkan teks untuk memanjang ke bawah */
            vertical-align: top; /* Memastikan teks di atas */
        }
        th {
            background-color: #f2f2f2;
        }
        .signature {
            position: absolute; /* Mengatur posisi absolut untuk tanda tangan */
            bottom: 20mm; /* Jarak dari bawah halaman */
            right: 20mm; /* Jarak dari kanan halaman */
            text-align: center; /* Pusatkan teks tanda tangan */
        }
    </style>
</head>
<body>
    <h1 style="font-size: 14px;">Data Fasilitas</h1> <!-- Mengurangi ukuran font judul -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th> <!-- Kolom nomor -->
                <th style="width: 70px;">Kode Region</th>
                <th style="width: 70px;">Kode Site</th>
                <th style="width: 50px;">No Rack</th>
                <th style="width: 70px;">Kode Fasilitas</th>
                <th style="width: 50px;">Fasilitas Ke</th>
                <th style="width: 70px;">Kode Brand</th>
                <th style="width: 50px;">Type</th>
                <th style="width: 70px;">Serial Number</th>
                <th style="width: 50px;">Jumlah Fasilitas</th>
                <th style="width: 50px;">Status</th>
                <th style="width: 50px;">U Awal</th>
                <th style="width: 50px;">U Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fasilitas as $index => $item) <!-- Menambahkan index untuk nomor -->
                <tr>
                    <td>{{ $index + 1 }}</td> <!-- Menampilkan nomor urut -->
                    <td>{{ $item->kode_region }}</td>
                    <td>{{ $item->kode_site }}</td>
                    <td>{{ $item->no_rack }}</td>
                    <td>{{ $item->kode_fasilitas }}</td>
                    <td>{{ $item->fasilitas_ke }}</td>
                    <td>{{ $item->kode_brand }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->serialnumber }}</td>
                    <td>{{ $item->jml_fasilitas }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->uawal }}</td>
                    <td>{{ $item->uakhir }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>__________________________</p>
        <p>Tanda Tangan</p>
    </div>
</body>
</html>
