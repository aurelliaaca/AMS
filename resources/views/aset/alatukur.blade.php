@extends('layouts.sidebar')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
        }

        body {
            min-height: 100vh;
        }

        .container {
            box-shadow: 2px 2px 10px #9497f5;
            margin: 5px auto;
            padding: 20px;
            background-color: #fff;
            max-width: 100%;
            border-radius: 5px;
            transition: 0.3s linear all;
        }

        .container:hover {
            box-shadow: 4px 4px 20px #DADADA;
        }

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

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            background-color: #4f52ba;
            color: #fff;
            padding: 10px;
            text-align: left;
        }

        .table tbody tr {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .table tbody td {
            padding: 10px;
            font-size: 14px;
            color: #333;
        }

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .edit-btn {
            background-color: #4f52ba;
            color: white;
            margin-right: 5px;
        }

        .delete-btn {
            background-color: red;
            color: white;
        }

        select {
            background-color: #4f52ba;
            color: white;
            border: none;
            padding: 5px;
            border-radius: 5px;
            outline: none;
        }

        select option {
            background-color: #4f52ba;
            color: white;
        }
    </style>

    <div class="main">
        <div class="container">
            <div class="header">
                <h2><strong>Data Alat Ukur</strong></h2>
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTable()" />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="alatukurTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>
                                <select id="roFilter" onchange="filterTable()">
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
                            <th>Kode</th>
                            <th>Nama Alat</th>
                            <th>Merk</th>
                            <th>Type</th>
                            <th>Serial Number</th>
                            <th>Tahun Perolehan</th>
                            <th>
                                <select id="kondisiFilter" onchange="filterTable()">
                                    <option value="">Kondisi Alat</option>
                                    <option value="Normal">Normal</option>
                                    <option value="Rusak Total">Rusak Total</option>
                                    <option value="Rusak Sedang">Rusak Sedang</option>
                                </select>
                            </th>
                            <th>Harga Pembelian</th>
                            <th>No Kontrak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alat_ukur as $alat)
                        <tr>
                            <td>{{ $alat->urutan }}</td>
                            <td class="ro">{{ $alat->RO }}</td>
                            <td>{{ $alat->kode }}</td>
                            <td>{{ $alat->nama_alat }}</td>
                            <td>{{ $alat->merk }}</td>
                            <td>{{ $alat->type }}</td>
                            <td>{{ $alat->serial_number }}</td>
                            <td>{{ $alat->tahun_perolehan }}</td>
                            <td class="kondisi">{{ $alat->kondisi_alat }}</td>
                            <td>{{ $alat->harga_pembelian }}</td>
                            <td>{{ $alat->no_kontrak_spk }}</td>
                            <td>
                                <button class="edit-btn">Edit</button>
                                <button class="delete-btn" onclick="deleteRow(this)">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterTable() {
            const roFilter = document.getElementById("roFilter").value.toLowerCase();
            const kondisiFilter = document.getElementById("kondisiFilter").value.toLowerCase();
            const rows = document.querySelectorAll("#alatukurTable tbody tr");

            rows.forEach(row => {
                const roCell = row.querySelector(".ro").textContent.toLowerCase();
                const kondisiCell = row.querySelector(".kondisi").textContent.toLowerCase();

                const matchesRO = roFilter === "" || roCell.includes(roFilter);
                const matchesKondisi = kondisiFilter === "" || kondisiCell.includes(kondisiFilter);

                if (matchesRO && matchesKondisi) {
                    row.style.display = ""; // Menampilkan baris
                } else {
                    row.style.display = "none"; // Menyembunyikan baris
                }
            });
        }

        function searchTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll("#alatukurTable tbody tr");

            rows.forEach(row => {
                const cells = row.getElementsByTagName("td");
                let matchesSearch = false;
                
                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().includes(filter)) {
                        matchesSearch = true;
                        break;
                    }
                }

                if (matchesSearch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
@endsection
