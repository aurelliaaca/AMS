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
                                <select class="filter-select" id="sexFilter" onchange="filterTable()">
                                    <option>Sex</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="ageFilter" onchange="filterTable()">
                                    <option>Age</option>
                                    <option>20</option>
                                    <option>35</option>
                                    <option>28</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="educationFilter" onchange="filterTable()">
                                    <option>Education</option>
                                    <option>Bachelor</option>
                                    <option>Doctor</option>
                                    <option>Doctor of Philosophy</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="resumeScoreFilter" onchange="filterTable()">
                                    <option>Resume Score</option>
                                    <option>70.5</option>
                                    <option>20</option>
                                    <option>43.5</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="videoScoreFilter" onchange="filterTable()">
                                    <option>Video Score</option>
                                    <option>95</option>
                                    <option>54.5</option>
                                    <option>73</option>
                                </select>
                            </th>
                            <th>
                                <select class="filter-select" id="statusFilter" onchange="filterTable()">
                                    <option>Status</option>
                                    <option>Finished</option>
                                    <option>Live</option>
                                    <option>Pending</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Male</td>
                            <td>35</td>
                            <td>Bachelor</td>
                            <td>70.5</td>
                            <td>95</td>
                            <td><span class="status status-finished">Finished</span></td>
                        </tr>
                        <tr>
                            <td>Female</td>
                            <td>20</td>
                            <td>Bachelor</td>
                            <td>20</td>
                            <td>54.5</td>
                            <td><span class="status status-finished">Finished</span></td>
                        </tr>
                        <tr>
                            <td>Female</td>
                            <td>28</td>
                            <td>Doctor of Philosophy</td>
                            <td>43.5</td>
                            <td>73</td>
                            <td><span class="status status-live">Live</span></td>
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
    </script>
@endsection