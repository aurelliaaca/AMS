<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="importModal" class="modal-overlay" style="display: none;">
    <div class="modal-content import-modal" style="max-height: 65vh; overflow: auto; width: 65svw;">
        <button class="modal-close-btn" onclick="closeImportModal()">×</button>
        <h2>Import Data Alatukur</h2>
        <form id="importForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="importFile">Pilih File Excel</label>
                <input type="file" id="importFile" name="file" accept=".xlsx, .xls" required>
            </div>
            <div class="form-group" style="max-height: 50vh; overflow: auto;">
                <label>Dengan Format Tabel</label>
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 1%;">kode_region</th>
                            <th style="width: 1%;">kode_alatukur</th>
                            <th style="width: 1%;">kode_brand</th>
                            <th style="width: 1%;">type</th>
                            <th style="width: 3%;">serialnumber</th>
                            <th style="width: 1%;">alatukur_ke</th>
                            <th style="width: 1%;">tahunperolehan</th>
                            <th style="width: 1%;">kondisi</th>
                            <th style="width: 3%;">keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9">Format file harus Excel (.xlsx atau .xls)</td>
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
<div id="importLoadingIndicator" style="display: none;">
    <img src="loading.gif" alt="Loading..." width="30">
</div>


<script>
document.getElementById('importForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah form submit secara default

    submitImport(); // Memanggil fungsi yang sudah ada untuk meng-handle upload
});
</script>

