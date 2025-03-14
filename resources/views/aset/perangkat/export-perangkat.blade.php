 <!DOCTYPE html>
<html>
<head>
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
            text-align: center;
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
        th:nth-child(3), td:nth-child(3) { width: 100px; } /* Site */
        th:nth-child(4), td:nth-child(4) { width: 70px; } /* No Rack */
        th:nth-child(5), td:nth-child(5) { width: 100px; } /* Jenis Perangkat */
        th:nth-child(6), td:nth-child(6) { width: 70px; } /* Perangkat Ke */
        th:nth-child(7), td:nth-child(7) { width: 100px; } /* Brand */
        th:nth-child(8), td:nth-child(8) { width: 100px; } /* Type */
        th:nth-child(9), td:nth-child(9) { width: 70px; } /* U Awal */
        th:nth-child(10), td:nth-child(10) { width: 70px; } /* U Akhir */

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer {
            position: absolute;
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
        .text-center {
            text-align: center;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="title">Data Perangkat</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Hostname</th>
                <th>Region</th>
                <th>Site</th>
                <th>No Rack</th>
                <th>Perangkat</th>
                <th>Brand</th>
                <th>Type</th>
                <th class="text-center">U Awal</th>
                <th class="text-center">U Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perangkat as $index => $item)
                @php
                    $hostname = implode('-', array_filter([
                        $item->kode_region,
                        $item->kode_site,
                        $item->no_rack,
                        $item->kode_perangkat,
                        $item->perangkat_ke,
                        $item->kode_brand,
                        $item->type
                    ]));
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hostname ?: '-' }}</td>
                    <td>{{ $item->region->nama_region ?? '-' }}</td>
                    <td>{{ $item->site->nama_site ?? '-' }}</td>
                    <td>{{ $item->no_rack ?? '-' }}</td>
                    <td>{{ $item->jenisperangkat->nama_perangkat ?? '-' }}</td>
                    <td>{{ $item->brand->nama_brand ?? '-' }}</td>
                    <td>{{ $item->type ?? '-' }}</td>
                    <td class="text-center">{{ $item->uawal ?? '-' }}</td>
                    <td class="text-center">{{ $item->uakhir ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-content">
            <span>Tanggal Ekspor: {{ now()->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}</span>
            <div class="signature-container">
                <p>Tanda Tangan</p>
                <p></p>
                <p style="padding-top: 5px;">__________________________</p>
            </div>
        </div>
    </div>
</body>
</html> 