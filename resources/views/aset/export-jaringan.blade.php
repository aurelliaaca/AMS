<!DOCTYPE html>
<html>
<head>
    <title>Data Jaringan</title>
    <style>
        @page {
            size: A4 landscape; /* Mengatur ukuran halaman menjadi landscape */
            margin: 20mm; /* Mengatur margin halaman */
        }
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            color: black;
            position: relative;
            height: 100vh;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
            font-size: 10px;
            word-wrap: break-word;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        /* Atur lebar kolom */
        th:nth-child(1), td:nth-child(1) { width: 30px; } /* No */
        th:nth-child(2), td:nth-child(2) { width: 100px; } /* Region */
        th:nth-child(3), td:nth-child(3) { width: 70px; } /* Tipe Jaringan */
        th:nth-child(4), td:nth-child(4) { width: 70px; } /* Segmen */
        th:nth-child(5), td:nth-child(5) { width: 70px; } /* Jartatup/Jartaplok */
        th:nth-child(6), td:nth-child(6) { width: 70px; } /* Mainlink/Backuplink */
        th:nth-child(7), td:nth-child(7) { width: 50px; } /* Panjang */
        th:nth-child(8), td:nth-child(8) { width: 50px; } /* Panjang Drawing */
        th:nth-child(9), td:nth-child(9) { width: 50px; } /* Jumlah Core */
        th:nth-child(10), td:nth-child(10) { width: 70px; } /* Jenis Kabel */
        th:nth-child(11), td:nth-child(11) { width: 70px; } /* Tipe Kabel */
        th:nth-child(12), td:nth-child(12) { width: 50px; } /* Status */
        th:nth-child(13), td:nth-child(13) { width: 70px; } /* Keterangan */
        th:nth-child(14), td:nth-child(14) { width: 70px; } /* Keterangan 2 */
        th:nth-child(15), td:nth-child(15) { width: 70px; } /* Kode Site Insan */
        th:nth-child(16), td:nth-child(16) { width: 70px; } /* DCI EQX */
        th:nth-child(17), td:nth-child(17) { width: 70px; } /* Update */
        th:nth-child(18), td:nth-child(18) { width: 70px; } /* Route */

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer {
            position: absolute;
            bottom: 5px;
            left: 20px;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
            width: calc(100% - 40px);
            padding-right: 20px;
        }
        .signature-container {
            position: static;
            text-align: right;
            width: 200px;
            margin-left: auto;
        }
        .signature-container p {
            margin: 0;
            text-align: center;
        }
        .signature-container p:nth-child(2) {
            margin-top: 30px;
        }
        .signature-container p:last-child {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="title">Data Jaringan</div>
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

    <div class="footer">
        <span>Tanggal Ekspor: {{ now()->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}</span>
        <div class="signature-container">
            <p>Tanda Tangan</p>
            <p></p>
            <p style="padding-top: 5px;">__________________________</p>
        </div>
    </div>
</body>
</html>

