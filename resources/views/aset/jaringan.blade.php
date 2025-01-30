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
            justify-content: flex-end;
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
        font-weight: bold;
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

        .table thead th:nth-child(3),
        .table tbody td:nth-child(3) {
            min-width: 300px;
            max-width: 500px;
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

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .table {
            min-width: 800px;
            width: auto;
        }

        .table th {
            text-align: left;
        }

        .table th:nth-child(1), .table td:nth-child(1) {
            width: 150px;
        }
    </style>

    <div class="main">
        <div class="container">
            <div class="header">
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
                            <th>
                                <select class="filter-select" id="tipeJaringanFilter" onchange="filterTable()">
                                    <option value="">Tipe Jaringan</option>
                                    <option value="Backbone">Backbone</option>
                                    <option value="ROW PGN">ROW PGN</option>
                                    <option value="Lastmile">Lastmile</option>
                                </select>
                            </th>
                            <th>Segmen</th>
                            <th>
                                <select class="filter-select" id="jartatupFilter" onchange="filterTable()">
                                    <option value="">Jartatup Jartaplok</option>
                                    <option value="Jartatup">Jartatup</option>
                                    <option value="Jartaplok">Jartaplok</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="mainlinkFilter" onchange="filterTable()">
                                    <option value="">Mainlink Backuplink</option>
                                    <option value="Single">Single</option>
                                    <option value="Protected">Protected</option>
                                </select>
                            </th>
                            <th>Panjang</th>
                            <th>Panjang Drawing</th>
                            <th>
                                <select class="filter-select" id="coreFilter" onchange="filterTable()">
                                    <option value="">Jumlah Core</option>
                                    <option value="12">12</option>
                                    <option value="24">24</option>
                                    <option value="48">48</option>
                                    <option value="96">96</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="kabelFilter" onchange="filterTable()">
                                    <option value="">Jenis Kabel</option>
                                    <option value="Kabel Tanah">Kabel Tanah</option>
                                    <option value="Kabel Uadara">Kabel Uadara</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="tipeKabelFilter" onchange="filterTable()">
                                    <option value="">Tipe Kabel</option>
                                    <option value="G652D">G652D</option>
                                    <option value="G655C">G655C</option>
                                    <option value="G654C">G654C</option>
                                    <option value="G654B">G654B</option>
                                </select>
                            </th>
                            <th>Kode Site Insan</th>
                            <th>
                                <select class="filter-select" id="travellingFilter" onchange="filterTable()">
                                    <option value="">Travelling Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="2:00:00">2:00:00</option>
                                    <option value="6:00:00">6:00:00</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="verificationFilter" onchange="filterTable()">
                                    <option value="">Verification Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="2:00:00">2:00:00</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="restorationFilter" onchange="filterTable()">
                                    <option value="">Restoration Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="3:00:00">3:00:00</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="correctiveFilter" onchange="filterTable()">
                                    <option value="">Total Corrective Time</option>
                                    <option value="1:00:00">1:00:00</option>
                                    <option value="3:00:00">3:00:00</option>
                                    <option value="4:00:00">4:00:00</option>
                                    <option value="7:00:00">7:00:00</option>
                                    <option value="9:00:00">9:00:00</option>
                                    <option value="11:00:00">11:00:00</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 96 core HH Tunas-POP Tunas</td>
                            <td>Jartatup</td>
                            <td>Single</td>
                            <td>311</td>
                            <td>311</td>
                            <td>96</td>
                            <td>Kabel Tanah</td>
                            <td>G652D</td>
                            <td>BTMBB1</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>3:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 24 core MH TPCO - POP Kabil</td>
                            <td>Jartatup</td>
                            <td>Single</td>
                            <td>3.800</td>
                            <td>3.800</td>
                            <td>24</td>
                            <td>Kabel Tanah</td>
                            <td>G652D</td>
                            <td>BTMBB2</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>3:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 48 Core FTTX POP Kabil - HH Simp. Taiwan</td>
                            <td>Jartatup</td>
                            <td>Single</td>
                            <td>1.700</td>
                            <td>1.700</td>
                            <td>48</td>
                            <td>Kabel Tanah</td>
                            <td>G652D</td>
                            <td>BTMBB3</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>3:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 24 core HH KDA -  HH TPCO - HH Bosowa</td>
                            <td>Jartatup</td>
                            <td>Single</td>
                            <td>9.600</td>
                            <td>9.600</td>
                            <td>24</td>
                            <td>Kabel Tanah</td>
                            <td>G652D</td>
                            <td>BTMBB4</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>3:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 96 core BTC - Panaran</td>
                            <td>Jartatup</td>
                            <td>Protected</td>
                            <td>23.410</td>
                            <td>21.600</td>
                            <td>96</td>
                            <td>Kabel Tanah</td>
                            <td>G652D</td>
                            <td>BTMBB5</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>1:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 96 core Feeder FTTH KDA HH Tunas - HH Perum KDA</td>
                            <td>Jartaplok</td>
                            <td>Single</td>
                            <td>1.780</td>
                            <td>1.500</td>
                            <td>96</td>
                            <td>Kabel Tanah</td>
                            <td>G652D</td>
                            <td>BTMBB6</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>1:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 48 core Feeder FTTH KDA HH Perum KDA - Slack 28</td>
                            <td>Jartaplok</td>
                            <td>Single</td>
                            <td>764</td>
                            <td>519</td>
                            <td>48</td>
                            <td>Kabel Udara</td>
                            <td>G652D</td>
                            <td>BTMBB7</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>1:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 12 core Distribusi FTTH KDA 01</td>
                            <td>Jartaplok</td>
                            <td>Single</td>
                            <td>415</td>
                            <td>226</td>
                            <td>12</td>
                            <td>Kabel Udara</td>
                            <td>G652D</td>
                            <td>BTMBB8</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>1:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 12 core Distribusi FTTH KDA 03</td>
                            <td>Jartaplok</td>
                            <td>Single</td>
                            <td>445</td>
                            <td>290</td>
                            <td>12</td>
                            <td>Kabel Udara</td>
                            <td>G652D</td>
                            <td>BTMBB9</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>1:00:00</td>
                            <td>3:00:00</td>
                        </tr>
                        <tr>
                            <td>Batam</td>
                            <td>Backbone</td>
                            <td>Backbone 12 Core Feeder FTTH Bida Asri HH Tunas - Perum Bida Asri </td>
                            <td>Jartaplok</td>
                            <td>Single</td>
                            <td>445</td>
                            <td>290</td>
                            <td>12</td>
                            <td>Kabel Udara</td>
                            <td>G652D</td>
                            <td>BTMBB10</td>
                            <td>2:00:00</td>
                            <td>-</td>
                            <td>1:00:00</td>
                            <td>3:00:00</td>
                        </tr>
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

        function filterTable() {
            const roFilter = document.getElementById('roFilter').value.toLowerCase();
            const tipeJaringanFilter = document.getElementById('tipeJaringanFilter').value.toLowerCase();
            const jartatupFilter = document.getElementById('jartatupFilter').value.toLowerCase();
            const mainlinkFilter = document.getElementById('mainlinkFilter').value.toLowerCase();
            const coreFilter = document.getElementById('coreFilter').value.toLowerCase();
            const kabelFilter = document.getElementById('kabelFilter').value.toLowerCase();
            const tipeKabelFilter = document.getElementById('tipeKabelFilter').value.toLowerCase();
            const travellingFilter = document.getElementById('travellingFilter').value.toLowerCase();
            const verificationFilter = document.getElementById('verificationFilter').value.toLowerCase();
            const restorationFilter = document.getElementById('restorationFilter').value.toLowerCase();
            const correctiveFilter = document.getElementById('correctiveFilter').value.toLowerCase();

            const table = document.querySelector('.table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let display = true;

                // Pastikan row memiliki data (tidak hanya header)
                if (cells.length > 0) {
                    // Filter berdasarkan RO (kolom ke-0)
                    if (roFilter && cells[0].innerText.toLowerCase() !== roFilter) {
                        display = false;
                    }
                    // Filter berdasarkan Tipe Jaringan (kolom ke-1)
                    if (tipeJaringanFilter && cells[1].innerText.toLowerCase() !== tipeJaringanFilter) {
                        display = false;
                    }
                    // Filter berdasarkan Jartatup (kolom ke-3)
                    if (jartatupFilter && cells[3].innerText.toLowerCase() !== jartatupFilter) {
                        display = false;
                    }
                    // Filter berdasarkan Mainlink (kolom ke-4)
                    if (mainlinkFilter && cells[4].innerText.toLowerCase() !== mainlinkFilter) {
                        display = false;
                    }
                    if (coreFilter && cells[7].innerText.toLowerCase() !== coreFilter) {
                        display = false;
                    }
                    if (kabelFilter && cells[8].innerText.toLowerCase() !== kabelFilter) {
                        display = false;
                    }
                    if (tipeKabelFilter && cells[9].innerText.toLowerCase() !== tipeKabelFilter) {
                        display = false;
                    }
                    if (travellingFilter && cells[9].innerText.toLowerCase() !== travellingFilter) {
                        display = false;
                    }
                    if (verificationFilter && cells[9].innerText.toLowerCase() !== verificationFilter) {
                        display = false;
                    }
                    if (restorationFilter && cells[9].innerText.toLowerCase() !== restorationFilter) {
                        display = false;
                    }
                    if (correctiveFilter && cells[9].innerText.toLowerCase() !== correctiveFilter) {
                        display = false;
                    }
                }

                rows[i].style.display = display ? '' : 'none';
            }
        }
    </script>
@endsection