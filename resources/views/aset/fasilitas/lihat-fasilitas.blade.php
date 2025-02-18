<div id="lihatFasilitasModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeLihatFasilitasModal()">×</button>
        <h2>Detail Fasilitas</h2>
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
                    <label>Site</label>
                    <p id="siteView"></p>
                </div>
                <div class="form-group">
                    <label>Fasilitas</label>
                    <p id="fasilitasView"></p>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <p id="brandView"></p>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <p id="typeView"></p>
                </div>
                <div class="form-group">
                    <label>Serial Number</label>
                    <p id="serialnumberView"></p>
                </div>
            </div>
            <div class="right-column">
                <div class="form-group">
                    <label>Status/Keterangan</label>
                    <p id="statusView"></p>
                </div><div class="form-group">
                    <label>Jumlah Fasilitas</label>
                    <p id="jmlfasilitasView"></p>
                </div><div class="form-group">
                    <label>No Rack</label>
                    <p id="noRackView"></p>
                </div>
                <div class="form-group">
                    <label>U Awal</label>
                    <p id="uawalView"></p>
                </div>
                <div class="form-group">
                    <label>U Akhir</label>
                    <p id="uakhirView"></p>
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
// Fungsi untuk menampilkan detail fasilitas
function lihatFasilitas(id_fasilitas) {
    $.get(`/get-fasilitas/${id_fasilitas}`, function(response) {
        if (response.success) {
            const fasilitas = response.fasilitas;
            const values = [
                fasilitas.kode_region,
                fasilitas.kode_site,
                fasilitas.no_rack,
                fasilitas.kode_fasilitas,
                fasilitas.fasilitas_ke,
                fasilitas.kode_brand,
                fasilitas.type
            ];

            // Filter nilai yang bukan null atau undefined, lalu gabungkan dengan "-"
            $('#hostnameView').text(values.filter(val => val).join('-'));
            $('#regionView').text(fasilitas.nama_region);
            $('#siteView').text(fasilitas.nama_site);
            $('#fasilitasView').text(fasilitas.nama_fasilitas);
            $('#brandView').text(fasilitas.nama_brand || '-');
            $('#typeView').text(fasilitas.type || '-');
            $('#serialnumberView').text(fasilitas.serialnumber || '-');
            $('#jmlfasilitasView').text(fasilitas.jml_fasilitas || '-');
            $('#statusView').text(fasilitas.status || '-');
            $('#noRackView').text(fasilitas.no_rack ? `Rack ${fasilitas.no_rack}` : '-');
            $('#uawalView').text(fasilitas.uawal || '-');
            $('#uakhirView').text(fasilitas.uakhir || '-');

            // Tampilkan modal
            document.getElementById("lihatFasilitasModal").style.display = "flex";

            // Ambil data histori fasilitas
            $.get(`/histori-fasilitas/${id_fasilitas}`, function(response) {
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
                                    <td>${item.aksi}</td>
                                    <td>${formattedTanggal}</td>
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
function closeLihatFasilitasModal() {
    document.getElementById("lihatFasilitasModal").style.display = "none";
}
</script>