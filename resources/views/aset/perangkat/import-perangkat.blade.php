<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="importPerangkatModal" class="modal-overlay" style="display: none;">
    <div class="modal-content import-modal" style="max-height: 65vh; overflow: auto; width: 65vw;">
        <button class="modal-close-btn" onclick="closeImportPerangkatModal()">×</button>
        <h2>Import Data Perangkat</h2>
        <form id="importPerangkatForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Pilih File Excel</label>
                <input type="file" id="file" name="file" accept=".xlsx, .xls" required>
            </div>
            <div class="form-group">
                <label>Dengan Format Tabel</label>
                <table>
                    <thead>
                        <tr>
                            <th>kode_region</th>
                            <th>kode_site</th>
                            <th>no_rack</th>
                            <th>kode_perangkat</th>
                            <th>kode_brand</th>
                            <th>type</th>
                            <th>uawal</th>
                            <th>uakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8">Format file harus Excel (.xlsx atau .xls)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button">Import</button>
            </div>
        </form>
    </div>
</div>
