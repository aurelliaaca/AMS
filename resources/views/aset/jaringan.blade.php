@extends('layouts.sidebar')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        
        /* Reset & Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        body {
            min-height: 100vh;
        }

        /* Container Styles */
        .container {
            box-shadow: 2px 2px 10px #9497f5;
            margin: 5px auto;
            padding: 20px 10px;
            background-color: #fff;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            transition: 0.3s linear all;
        }

        .container:hover {
            box-shadow: 4px 4px 20px #DADADA;
        }

        /* Header Styles */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-title {
            text-align: left;
            margin: 0;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #4f52ba;
            border-radius: 8px;
            padding: 8px 12px;
            width: 300px;
        }

        .search-bar input {
            border: none;
            background: none;
            outline: none;
            font-size: 14px;
            width: 100%;
            color: #fff;
        }

        .search-bar input::placeholder {
            color: #fff;
        }

        .search-bar svg {
            margin-right: 8px;
            color: #fff;
        }

        .filter-select {
            border: none;
            border-radius: 8px;
            background-color: #4f52ba; 
            padding: 8px 12px;
            font-size: 14px;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
        }

        /* Table Styles */
        .table {
            width: 100%;
            font-size: 18px;
            border-collapse: collapse;
            table-layout: auto;
            padding-left: 30px;
        }

        .table thead th {
            font-weight: bold;
            text-align: left;
            padding: 15px;
            color: #fff;
            background-color: #4f52ba;
            font-size: 16px;
        }

        .table thead th:nth-child(12),
        .table tbody td:nth-child(12) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(8) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table tbody td:nth-child(8) {
            padding-left: 60px;
        }

        .table tbody th:nth-child(13){
            padding-left: 70px;
        }

        .table thead th:nth-child(3) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(5) {
            min-width: 250px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(6) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(7) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(8) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(9) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(10) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(11) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(13) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(14) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(15) {
            min-width: 175px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(16) {
            min-width: 200px;
            word-wrap: break-word;
        }

        .table thead th:nth-child(4),
        .table tbody td:nth-child(4) {
            min-width: 300px;
            max-width: 500px;
            text-align: middle;
            word-wrap: break-word;
        }

        .table tbody tr {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            padding: 15px;
        }

        .table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .table tbody td {
            padding: 15px;
            font-size: 16px;
            color: #333;
            vertical-align: middle;
            padding-left: 30px;
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .table th {
            text-align: left;
        }

        .table th:nth-child(1), .table td:nth-child(1) {
            width: 150px;
        }

        /* Tambahkan padding ke semua td */
        .table td {
            padding-left: 10px; /* Atur padding kiri untuk menggeser konten ke kanan */
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th {
            background-color: #4f52ba;
            color: #fff;
            padding: 12px;
            text-align: center;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: rgba(79, 82, 186, 0.2);
        }
    </style>

    <div class="main">
        <div class="container">
            <div class="header-container">
                <h2 class="header-title"><strong>Data Jaringan</strong></h2>
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                        <path fill="currentColor" d="M10 18a8 8 0 1 1 0-16a8 8 0 0 1 0 16Zm7.707-2.707a1 1 0 0 0 0 1.414l3 3a1 1 0 0 0 1.414-1.414l-3-3a1 1 0 0 0-1.414 0Z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTable()" />
                </div>
            </div>
            <div>
            <div class="table-container">
                <table id="jaringanTable" class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="roFilter" onchange="filterTable()">
                                    <option value="">RO</option>
                                    <option value="Batam">Batam</option>
                                    <option value="Bekasi">Bekasi</option>
                                    <option value="Cilegon">Cilegon</option>
                                    <option value="Jabatim">Jabatim</option>
                                    <option value="Jakarta">Jakarta</option>
                                    <option value="Jambi">Jambi</option>
                                    <option value="Lampung">Lampung</option>
                                    <option value="Palembang">Palembang</option>
                                    <option value="Sumbagut">Sumbagut</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="tipeJaringanFilter" onchange="filterTable()">
                                    <option value="">Tipe Jaringan</option>
                                    <option value="Backbone">Backbone</option>
                                    <option value="ROW PGN">ROW PGN</option>
                                    <option value="Lastmile">Lastmile</option>
                                </select>
                            </th>
                            <th style="width: 10%;">Segmen</th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="jartatupFilter" onchange="filterTable()">
                                    <option value="">Jartatup Jartaplok</option>
                                    <option value="Jartatup">Jartatup</option>
                                    <option value="Jartaplok">Jartaplok</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="mainlinkFilter" onchange="filterTable()">
                                    <option value="">Mainlink Backuplink</option>
                                    <option value="Single">Single</option>
                                    <option value="Protected">Protected</option>
                                </select>
                            </th>
                            <th style="width: 10%;">Panjang</th>
                            <th style="width: 10%;">Panjang Drawing</th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="jumlahCoreFilter" onchange="filterTable()">
                                    <option value="">Jumlah Core</option>
                                    <option value="12">12</option>
                                    <option value="24">24</option>
                                    <option value="48">48</option>
                                    <option value="96">96</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="jenisKabelFilter" onchange="filterTable()">
                                    <option value="">Jenis Kabel</option>
                                    <option value="Kabel Tanah">Kabel Tanah</option>
                                    <option value="Kabel Udara">Kabel Udara</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="tipeKabelFilter" onchange="filterTable()">
                                    <option value="">Tipe Kabel</option>
                                    <option value="G652D">G652D</option>
                                    <option value="G655C">G655C</option>
                                    <option value="G654C">G654C</option>
                                    <option value="G654B">G654B</option>
                                </select>
                            </th>
                            <th style="width: 10%;">Kode Site Insan</th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="travellingFilter" onchange="filterTable()">
                                    <option value="">Travelling Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="2:00:00">2:00:00</option>
                                    <option value="3:00:00">3:00:00</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="verificationFilter" onchange="filterTable()">
                                    <option value="">Verification Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="2:00:00">2:00:00</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="restorationFilter" onchange="filterTable()">
                                    <option value="">Restoration Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="3:00:00">3:00:00</option>
                                </select>
                            </th>
                            <th style="width: 10%;">
                                <select class="filter-select" id="correctiveFilter" onchange="filterTable()">
                                    <option value="">Total Corrective Time</option>
                                    <option value="0:00:00">0:00:00</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="2:00:00">2:00:00</option>
                                    <option value="3:00:00">3:00:00</option>
                                    <option value="4:00:00">4:00:00</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="dataRows">
                        @foreach($jaringanData as $data)
                        <tr>
                            <td>{{ $data->id_jaringan }}</td>
                            <td>{{ $data->RO }}</td>
                            <td>{{ $data->tipe_jaringan }}</td>
                            <td>{{ $data->segmen }}</td>
                            <td>{{ $data->jartatup_jartaplok }}</td>
                            <td>{{ $data->mainlink_backuplink}}</td>
                            <td>{{ $data->panjang }}</td>
                            <td>{{ $data->panjang_drawing }}</td>
                            <td>{{ $data->jumlah_core }}</td>
                            <td>{{ $data->jenis_kabel }}</td>
                            <td>{{ $data->tipe_kabel }}</td>
                            <td>{{ $data->kode_site_insan }}</td>
                            <td>{{ $data->travelling_time }}</td>
                            <td>{{ $data->verification_time }}</td>
                            <td>{{ $data->restoration_time }}</td>
                            <td>{{ $data->total_corrective_time }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
    initializeData();

    document.getElementById("searchInput").addEventListener("keyup", searchTable);
    document.querySelectorAll(".filter-select").forEach(select => {
        select.addEventListener("change", filterTable);
    });
});

let allData = [];

function initializeData() {
    const tableRows = document.querySelectorAll("#jaringanTable tbody tr");
    tableRows.forEach(row => {
        const rowData = {
            id: row.cells[0].textContent.trim(),
            ro: row.cells[1].textContent.trim(),
            tipe_jaringan: row.cells[2].textContent.trim(),
            segmen: row.cells[3].textContent.trim(),
            jartatup: row.cells[4].textContent.trim(),
            mainlink: row.cells[5].textContent.trim(),
            panjang: row.cells[6].textContent.trim(),
            panjang_drawing: row.cells[7].textContent.trim(),
            jumlah_core: row.cells[8].textContent.trim(),
            jenis_kabel: row.cells[9].textContent.trim(),
            tipe_kabel: row.cells[10].textContent.trim(),
            kode_site: row.cells[11].textContent.trim(),
            travelling: row.cells[12].textContent.trim(),
            verification: row.cells[13].textContent.trim(),
            restoration: row.cells[14].textContent.trim(),
            corrective: row.cells[15].textContent.trim(),
        };
        allData.push(rowData);
    });
}

function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#jaringanTable tbody tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function filterTable() {
    let roFilter = document.getElementById("roFilter").value.toLowerCase();
    let tipeJaringanFilter = document.getElementById("tipeJaringanFilter").value.toLowerCase();
    let jartatupFilter = document.getElementById("jartatupFilter").value.toLowerCase();
    let mainlinkFilter = document.getElementById("mainlinkFilter").value.toLowerCase();
    let jumlahCoreFilter = document.getElementById("jumlahCoreFilter").value.toLowerCase();
    let jenisKabelFilter = document.getElementById("jenisKabelFilter").value.toLowerCase();
    let tipeKabelFilter = document.getElementById("tipeKabelFilter").value.toLowerCase();
    let travellingFilter = document.getElementById("travellingFilter").value.toLowerCase();
    let verificationFilter = document.getElementById("verificationFilter").value.toLowerCase();
    let restorationFilter = document.getElementById("restorationFilter").value.toLowerCase();
    let correctiveFilter = document.getElementById("correctiveFilter").value.toLowerCase();

    document.querySelectorAll("#jaringanTable tbody tr").forEach(row => {
        let values = row.children;
        let match =
            (roFilter === "" || values[1].textContent.toLowerCase().includes(roFilter)) &&
            (tipeJaringanFilter === "" || values[2].textContent.toLowerCase().includes(tipeJaringanFilter)) &&
            (jartatupFilter === "" || values[4].textContent.toLowerCase().includes(jartatupFilter)) &&
            (mainlinkFilter === "" || values[5].textContent.toLowerCase().includes(mainlinkFilter)) &&
            (jumlahCoreFilter === "" || values[8].textContent.toLowerCase().includes(jumlahCoreFilter)) &&
            (jenisKabelFilter === "" || values[9].textContent.toLowerCase().includes(jenisKabelFilter)) &&
            (tipeKabelFilter === "" || values[10].textContent.toLowerCase().includes(tipeKabelFilter)) &&
            (travellingFilter === "" || values[12].textContent.toLowerCase().includes(travellingFilter)) &&
            (verificationFilter === "" || values[13].textContent.toLowerCase().includes(verificationFilter)) &&
            (restorationFilter === "" || values[14].textContent.toLowerCase().includes(restorationFilter)) &&
            (correctiveFilter === "" || values[15].textContent.toLowerCase().includes(correctiveFilter));

        row.style.display = match ? "" : "none";
    });
}

</script>

@endsection