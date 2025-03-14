<!DOCTYPE html>
<html>
<head>
    <title>Data Alatukur</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20mm;
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
        /* Mengatur lebar kolom */
        th:nth-child(1), td:nth-child(1) { width: 30px; } /* No */
        th:nth-child(2), td:nth-child(2) { width: 100px; } /* Hostname */
        th:nth-child(3), td:nth-child(3) { width: 70px; } /* Region */
        th:nth-child(4), td:nth-child(4) { width: 70px; } /* Alatukur */
        th:nth-child(5), td:nth-child(5) { width: 70px; } /* Brand */
        th:nth-child(6), td:nth-child(6) { width: 50px; } /* Type */
        th:nth-child(7), td:nth-child(7) { width: 70px; } /* Serial Number */
        th:nth-child(8), td:nth-child(8) { width: 50px; } /* Tahun Perolehan */
        th:nth-child(9), td:nth-child(9) { width: 50px; } /* Kondisi */
        th:nth-child(10), td:nth-child(10) { width: 50px; } /* Keterangan */

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
    <div class="title">Data Alatukur</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
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
            @php
                $counter = 1;
            @endphp
            @foreach ($alatukur as $item)
                <tr>
                    <td>{{ $counter++ }}</td>
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
