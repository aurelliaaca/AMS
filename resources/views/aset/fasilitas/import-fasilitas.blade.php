<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="importFasilitasModal" class="modal-overlay" style="display: none;">
    <div class="modal-content import-modal" style="max-height: 65vh; overflow: auto; width: 65svw;">
        <button class="modal-close-btn" onclick="closeImportFasilitasModal()">×</button>
        <h2>Import Data Fasilitas</h2>
        <form id="importFasilitasForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Pilih File Excel</label>
                <input type="file" id="file" name="file" accept=".xlsx, .xls" required>
            </div>
            <div class="form-group" style="max-height: 50vh; overflow: auto;">
                <label>Dengan Format Tabel</label>
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 1%;">kode_region</th>
                            <th style="width: 1%;">kode_site</th>
                            <th style="width: 3%;">kode_fasilitas</th>
                            <th style="width: 1%;">kode_brand</th>
                            <th style="width: 1%;">no_rack</th>
                            <th style="width: 1%;">type</th>
                            <th style="width: 3%;">serialnumber</th>
                            <th style="width: 3%;">jml_fasilitas</th>
                            <th style="width: 1%;">status</th>
                            <th style="width: 1%;">uawal</th>
                            <th style="width: 1%;">uakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="11">Format file harus Excel (.xlsx atau .xls)</td>
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
