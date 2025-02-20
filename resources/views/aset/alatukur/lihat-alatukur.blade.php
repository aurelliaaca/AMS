<div id="lihatAlatukurModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeLihatAlatukurModal()">×</button>
        <h2>Detail Alatukur</h2>
        <div class="form-container">
            <div class="left-column">
                <div class="form-group">
                    <label>Hostname</label>
                    <p id="hostnameView"></p>
                </div>
                <div class="form-group">
                    <label>Region</label>
                    <p id="regionView"></p>
                </div>
                <div class="form-group">
                    <label>Alatukur</label>
                    <p id="alatukurView"></p>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <p id="brandView"></p>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <p id="typeView"></p>
                </div>
            </div>
            <div class="right-column">
                    <div class="form-group">
                    <label>Serial Number</label>
                    <p id="serialnumberView"></p>
                </div>
                <div class="form-group">
                    <label>Tahun perolehan</label>
                    <p id="tahunperolehanView"></p>
                </div>
                <div class="form-group">
                    <label>Kondisi</label>
                    <p id="kondisiView"></p>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <p id="keteranganView"></p>
                </div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Aksi</th>
                    <th>Tanggal Perubahan</th>
                </tr>
            </thead>
            <tbody id="historiTableBody">
            </tbody>
        </table>
    </div>
</div>

<script>
// Fungsi untuk menampilkan detail alatukur
function lihatAlatukur(id_alatukur) {
    $.get(`/get-alatukur/${id_alatukur}`, function(response) {
        if (response.success) {
            const alatukur = response.alatukur;
            const values = [
                alatukur.kode_region, 
                alatukur.kode_alatukur, 
                alatukur.alatukur_ke, 
                alatukur.kode_brand, 
                alatukur.type
            ];

            // Filter nilai yang bukan null atau undefined, lalu gabungkan dengan "-"
            $('#hostnameView').text(values.filter(val => val).join('-'));
            $('#regionView').text(alatukur.nama_region);
            $('#alatukurView').text(alatukur.nama_alatukur);
            $('#brandView').text(alatukur.nama_brand || '-');
            $('#typeView').text(alatukur.type || '-');
            $('#serialnumberView').text(alatukur.serialnumber || '-');
            $('#tahunperolehanView').text(alatukur.tahunperolehan || '-');
            $('#kondisiView').text(alatukur.kondisi || '-');
            $('#keteranganView').text(alatukur.keterangan || '-');

            // Tampilkan modal
            document.getElementById("lihatAlatukurModal").style.display = "flex";

            // Ambil data histori alatukur
            $.get(`/histori-alatukur/${id_alatukur}`, function(response) {
                if (response.success) {
                    const histori = response.histori;
                    const tableBody = $('#historiTableBody');
                    tableBody.empty(); // Kosongkan tabel sebelum mengisi data baru

                    // Isi tabel dengan data histori
                    if (histori.length > 0) {
                        histori.forEach(item => {
                            const tanggal = new Date(item.tanggal_perubahan);
                            const options = {
                                weekday: 'long', day: '2-digit', month: 'long', year: 'numeric',
                                hour: '2-digit', minute: '2-digit'
                            };
                            const formattedTanggal = tanggal.toLocaleDateString('id-ID', options).replace('pukul', 'pada pukul');

                            tableBody.append(`
                                <tr>
                                    <td style="width: 50%; text-align: justify;">${item.histori}</td>
                                    <td style="width: 50%;">${formattedTanggal}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tableBody.append(`
                            <tr>
                                <td colspan="2" style="text-align: center;">Tidak ada histori tersedia</td>
                            </tr>
                        `);
                    }
                }
            });
        }
    });
}
function closeLihatAlatukurModal() {
    document.getElementById("lihatAlatukurModal").style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById("closeLihatAlatukurModal");
    const modalContent = document.querySelector(".modal-content");

    // Jika klik di luar modal-content, tutup modal
    if (event.target === modal) {
        closeLihatAlatukurModal();
    }
}
</script>