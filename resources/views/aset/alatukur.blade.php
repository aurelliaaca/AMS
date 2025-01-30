@extends('layouts.sidebar')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        
        /* Reset & Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", serif;
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
        }


        /* Table Styles */
        .table {
            width: 100%;
            font-size: 18px;
            border-collapse: separate;
            border-spacing: 0 20px;
        }

        .table thead th {
            font-weight: bold;
            text-align: left;
            padding: 15px;
            color: #fff;
            background-color: #4f52ba;
            font-size: 16px;
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
        }

        /* Status Styles */
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status-finished {
            background-color: #e0f7ea;
            color: #27ae60;
        }

        .status-live {
            background-color: #f1f1f9;
            color: #3e64ff;
        }
    </style>

    <div class="main">
        <div class="container">
            <div class="header">
            <h2><strong>Data Alat Ukur</strong></h2>
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                        <path fill="currentColor" d="M10 18a8 8 0 1 1 0-16a8 8 0 0 1 0 16Zm7.707-2.707a1 1 0 0 0 0 1.414l3 3a1 1 0 0 0 1.414-1.414l-3-3a1 1 0 0 0-1.414 0Z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTable()" />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <select class="filter-select" id="roFilter" onchange="filterTable()">
                                    <option>RO</option>
                                    <option value="Batam">Batam</option>
                                    <option value="Bekasi">Bekasi</option>
                                    <option value="Cilegon">Cilegon</option>
                                    <option value="Jabatim">Jabatim</option>
                                    <option value="Jakarta">Jakarta</option>
                                    <option value="Jambi">Jambi</option>
                                    <option value="Palembang">Palembang</option>
                                    <option value="Lampung">Lampung</option>
                                    <option value="Sumbagut">Sumbagut</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="kodeFilter" onchange="filterTable()">
                                    <option>Kode</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="namaalatFilter" onchange="filterTable()">
                                    <option>Nama Alat</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="merkFilter" onchange="filterTable()">
                                    <option>Merk</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="typeFilter" onchange="filterTable()">
                                    <option>Type</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="serialnumberFilter" onchange="filterTable()">
                                    <option>Serial Number</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="tahunperolehanFilter" onchange="filterTable()">
                                    <option>Tahun Perolehan</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="kondisialatFilter" onchange="filterTable()">
                                    <option>Kondisi Alat</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="hargapembelianFilter" onchange="filterTable()">
                                    <option>Harga Pembelian</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="nokontrakFilter" onchange="filterTable()">
                                    <option>No Kontrak SPK</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jabatim</td>
                            <td>SBY-FS-FIT#1</td>
                            <td>Fusion Splicer</td>
                            <td>Kyoritsu</td>
                            <td>Kyoritsu 4105</td>
                            <td>-</td>
                            <td></td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>Jabatim</td>
                            <td>SBY-OTDR-EXF#2</td>
                            <td>Optical Time Domain Reflectometer (OTDR)</td>
                            <td>Kyoritsu</td>
                            <td>Kew Snap 2033</td>
                            <td>E27137</td>
                            <td>2022≤</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>Jabatim</td>
                            <td>SBY-OPM-EXF#3</td>
                            <td>Optical Power Meter (OPM)</td>
                            <td>Fujikura</td>
                            <td>AFL FCC2, One Click 1.25 mm dan 2.0 mm</td>
                            <td>-</td>
                            <td>2021≤</td>
                            <td>Normal</td>
                        </tr>
                        <tbody>
                        <tr>
                            <td>Jabatim</td>
                            <td>SBY-OPM-EXF#4</td>
                            <td>Optical Power Meter (OPM)</td>
                            <td>Exfo</td>
                            <td>ELS-50-23BL-RB</td>
                            <td>1178618</td>
                            <td>2018≤</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>Jabatim</td>
                            <td>SBY-OLS-EXF#5</td>
                            <td>Optical Light Source (OLS)</td>
                            <td>Exfo</td>
                            <td>ELS-50-23BL-RB</td>
                            <td>1271696</td>
                            <td>2019≤</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>Jabatim</td>
                            <td>SBY-OLS-EXF#6</td>
                            <td>Optical Light Source (OLS)</td>
                            <td>Exfo</td>
                            <td>EPM-53RB</td>
                            <td>1173216</td>
                            <td>2018≤</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>Jabatim</td>
                            <td>SBY-VFL--#7</td>
                            <td>Visual Fault Locator (VFL)</td>
                            <td>-</td>
                            <td>HT-30</td>
                            <td>-</td>
                            <td>2019≤</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>Jabatim</td>
                            <td>SBY-FID-SMT#8</td>
                            <td>Fiber Identifier Detector (FID)</td>
                            <td>Sumitomo</td>
                            <td>FDT-2BC</td>
                            <td>34-00064</td>
                            <td>2017≤</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>Lampung</td>
                            <td>LPG-FS-FIT#1</td>
                            <td>Fusion Splicer</td>
                            <td>Fitel</td>
                            <td>S178A V2</td>
                            <td>33740</td>
                            <td>2017≤</td>
                            <td>Normal</td>
                        </tr>
                        <tr>
                            <td>Lampung</td>
                            <td>LPG-FS-FIT#2</td>
                            <td>Fusion Splicer</td>
                            <td>Fitel</td>
                            <td>S179A V2</td>
                            <td>5009</td>
                            <td>2018≤</td>
                            <td>Normal</td>
                        </tr>
                    </tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('.table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        if (cells[j].innerText.toLowerCase().includes(filter)) {
                            found = true;
                        }
                    }
                }

                if (found) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    </script>
@endsection