<!DOCTYPE html>
<html>
<head>
    <title>Export Alatukur</title>
    <style>
        @page {
            size: A4 landscape; /* Mengatur ukuran halaman menjadi landscape */
            margin: 20mm; /* Margin halaman */
        }

        body {
            font-family: Arial, sans-serif; /* Mengatur font */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Mengatur lebar kolom */
        th:nth-child(1), td:nth-child(1) { width: 15%; } /* Hostname */
        th:nth-child(2), td:nth-child(2) { width: 15%; } /* Region */
        th:nth-child(3), td:nth-child(3) { width: 15%; } /* Alatukur */
        th:nth-child(4), td:nth-child(4) { width: 15%; } /* Brand */
        th:nth-child(5), td:nth-child(5) { width: 10%; } /* Type */
        th:nth-child(6), td:nth-child(6) { width: 10%; } /* Serial Number */
        th:nth-child(7), td:nth-child(7) { width: 10%; } /* Tahun Perolehan */
        th:nth-child(8), td:nth-child(8) { width: 5%; }  /* Kondisi */
        th:nth-child(9), td:nth-child(9) { width: 5%; }  /* Keterangan */

        .footer {
            position: absolute; /* Mengatur posisi footer */
            bottom: 5px; /* Jarak dari bawah, ditingkatkan untuk menjauh dari tabel */
            left: 20px; /* Jarak dari kiri */
            font-size: 10px; /* Ukuran font footer */
        }

        .signature-container {
            position: absolute; /* Mengatur posisi tanda tangan */
            right: 20px; /* Jarak dari kanan */
            bottom: 20px; /* Jarak dari bawah */
            text-align: right; /* Menyelaraskan tanda tangan ke kanan */
            width: 200px; /* Lebar kolom tanda tangan */
        }

        .signature {
            border-top: 1px solid black; /* Garis untuk tanda tangan */
            margin-top: 5px; /* Jarak atas untuk tanda tangan */
            padding-top: 10px; /* Jarak dalam untuk tanda tangan */
            width: 100%; /* Lebar garis sesuai dengan kolom */
            text-align: center; /* Menyelaraskan garis ke tengah */
        }

        .title {
            text-align: center; /* Menyelaraskan judul ke tengah */
            font-size: 16px; /* Ukuran font judul */
            font-weight: bold; /* Menebalkan font judul */
            margin-bottom: 10px; /* Jarak bawah judul */
        }
    </style>
</head>
<body>
    <div class="title">Data Alatukur</div> <!-- Judul di tengah -->
    <table>
        <thead>
            <tr>
                <th>Hostname</th>
                <th>Region</th>
                <th>Alatukur</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Serial Number</th>
                <th>Tahun Perolehan</th>
                <th>Kondisi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alatukur as $item)
                <tr>
                    <td>{{ $item->hostname }}</td>
                    <td>{{ $item->kode_region }}</td>
                    <td>{{ $item->kode_alatukur }}</td>
                    <td>{{ $item->kode_brand }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->serialnumber }}</td>
                    <td>{{ $item->tahunperolehan }}</td>
                    <td>{{ $item->kondisi }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Tanggal Ekspor: {{ date('d-m-Y H:i:s') }}</div> <!-- Tanggal dan waktu di kiri paling bawah -->
    <div class="signature-container">
        <p style="text-align: center; margin: 0;">Tanda Tangan</p> <!-- Label untuk tanda tangan, diselaraskan ke tengah -->
        <p style="margin: 0; padding-top: 5px;">__________________________</p> <!-- Garis untuk tanda tangan -->
    </div>
</body>
</html>
