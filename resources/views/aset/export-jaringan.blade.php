<!DOCTYPE html>
<html>
<head>
    <title>Data Jaringan</title>
    <style>
        /* Tambahkan gaya CSS sesuai kebutuhan */
        body {
            margin: 0;
            padding: 0;
            position: relative; /* Untuk posisi tanda tangan */
            font-family: Arial, sans-serif; /* Font untuk dokumen */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto; /* Mengatur lebar kolom otomatis */
            margin-bottom: 100px; /* Memberikan ruang untuk tanda tangan */
        }
        th, td {
            border: 1px solid black;
            padding: 5px; /* Mengurangi padding untuk menghemat ruang */
            text-align: center; /* Menyelaraskan teks ke tengah */
            word-wrap: break-word; /* Memungkinkan teks panjang untuk memecah ke baris berikutnya */
        }
        th {
            background-color: #f2f2f2;
            font-size: 10px; /* Mengurangi ukuran font untuk header */
        }
        td {
            font-size: 9px; /* Mengurangi ukuran font untuk data */
        }
        /* Menentukan lebar kolom */
        th:nth-child(1) { width: 5%; } /* No */
        th:nth-child(2) { width: 10%; } /* Kode Region */
        th:nth-child(3) { width: 10%; } /* Tipe Jaringan */
        th:nth-child(4) { width: 10%; } /* No Rack */
        th:nth-child(5) { width: 10%; } /* Kode Jaringan */
        th:nth-child(6) { width: 10%; } /* Jaringan Ke */
        th:nth-child(7) { width: 10%; } /* Kode Brand */
        th:nth-child(8) { width: 10%; } /* Type */
        th:nth-child(9) { width: 10%; } /* Segmen */
        th:nth-child(10) { width: 10%; } /* Jartatup/Jartaplok */
        th:nth-child(11) { width: 10%; } /* Mainlink/Backuplink */
        th:nth-child(12) { width: 10%; } /* Panjang */
        th:nth-child(13) { width: 10%; } /* Panjang Drawing */
        th:nth-child(14) { width: 10%; } /* Jumlah Core */
        th:nth-child(15) { width: 10%; } /* Jenis Kabel */
        th:nth-child(16) { width: 10%; } /* Tipe Kabel */
        th:nth-child(17) { width: 10%; } /* Status */
        th:nth-child(18) { width: 10%; } /* Keterangan */

        .title {
            text-align: center; /* Menyelaraskan judul ke tengah */
            font-size: 16px; /* Ukuran font judul */
            font-weight: bold; /* Menebalkan font judul */
            margin-bottom: 10px; /* Jarak bawah judul */
        }
        .footer {
            position: absolute; /* Mengatur posisi footer */
            bottom: 50px; /* Jarak dari bawah */
            left: 20px; /* Jarak dari kiri */
            font-size: 10px; /* Ukuran font footer */
        }
        .signature-container {
            position: absolute; /* Mengatur posisi tanda tangan */
            right: 20px; /* Jarak dari kanan */
            text-align: right; /* Menyelaraskan tanda tangan ke kanan */
            width: 200px; /* Lebar kolom tanda tangan */
        }
        .signature {
            margin-top: 5px; /* Jarak atas untuk tanda tangan */
            padding-top: 10px; /* Jarak dalam untuk tanda tangan */
            width: 100%; /* Lebar garis sesuai dengan kolom */
            text-align: center; /* Menyelaraskan garis ke tengah */
        }
    </style>
</head>
<body>
    <h1 class="title">Data Jaringan</h1>
    <table>
        <thead>
            <tr style="background-color: #f8f8f8;">
                <th>No</th>
                <th>Region</th>
                <th>Tipe Jaringan</th>
                <th>Segmen</th>
                <th>Jartatup/Jartaplok</th>
                <th>Mainlink/Backuplink</th>
                <th>Panjang</th>
                <th>Panjang Drawing</th>
                <th>Jumlah Core</th>
                <th>Jenis Kabel</th>
                <th>Tipe Kabel</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Keterangan 2</th>
                <th>Kode Site Insan</th>
                <th>DCI EQX</th>
                <th>Update</th>
                <th>Route</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jaringan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->region ? $data->region->nama_region : 'Region Tidak Ditemukan' }}</td>
                    <td>{{ $data->tipe_jaringan }}</td>
                    <td>{{ $data->segmen }}</td>
                    <td>{{ $data->jartatup_jartaplok }}</td>
                    <td>{{ $data->mainlink_backuplink }}</td>
                    <td>{{ $data->panjang }}</td>
                    <td>{{ $data->panjang_drawing }}</td>
                    <td>{{ $data->jumlah_core }}</td>
                    <td>{{ $data->jenis_kabel }}</td>
                    <td>{{ $data->tipe_kabel }}</td>
                    <td>{{ $data->status }}</td>
                    <td>{{ $data->keterangan }}</td>
                    <td>{{ $data->keterangan_2 }}</td>
                    <td>{{ $data->kode_site_insan }}</td>
                    <td>{{ $data->dci_eqx }}</td>
                    <td>{{ $data->update }}</td>
                    <td>{{ $data->route }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Tanggal Ekspor: {{ date('d-m-Y H:i:s') }}</div> <!-- Tanggal dan waktu di kiri paling bawah -->
    <div class="signature-container">
        <p style="text-align: center; margin: 0;">Tanda Tangan</p> <!-- Label untuk tanda tangan, diselaraskan ke kiri -->
        <p class="signature"></p>
        <p style="margin: 0; padding-top: 5px;">__________________________</p> <!-- Garis untuk tanda tangan -->
    </div>
</body>
</html>

