<div id="lihatPerangkatModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeLihatPerangkatModal()">×</button>
        <h2>Detail Perangkat</h2>
        <div class="form-container">
            <div class="left-column">
                <div class="form-group">
                    <label>Kode</label>
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
                    <label>Perangkat</label>
                    <p id="perangkatView"></p>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <p id="brandView"></p>
                </div>
            </div>
            <div class="right-column">
                <div class="form-group">
                    <label>Type</label>
                    <p id="typeView"></p>
                </div>
                <div class="form-group">
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
// Fungsi untuk menampilkan detail perangkat
function lihatPerangkat(id_perangkat) {
    $.get(`/get-perangkat/${id_perangkat}`, function(response) {
        if (response.success) {
            const perangkat = response.perangkat;
            const values = [
                perangkat.kode_region,
                perangkat.kode_site,
                perangkat.no_rack,
                perangkat.kode_perangkat,
                perangkat.perangkat_ke,
                perangkat.kode_brand,
                perangkat.type
            ];

            // Filter nilai yang bukan null atau undefined, lalu gabungkan dengan "-"
            $('#hostnameView').text(values.filter(val => val).join('-'));
            $('#regionView').text(perangkat.nama_region);
            $('#siteView').text(perangkat.nama_site);
            $('#perangkatView').text(perangkat.nama_perangkat);
            $('#brandView').text(perangkat.nama_brand || '-');
            $('#typeView').text(perangkat.type || '-');
            $('#noRackView').text(`Rack ${perangkat.no_rack || '-'}`);
            $('#uawalView').text(perangkat.uawal || '-');
            $('#uakhirView').text(perangkat.uakhir || '-');

            // Tampilkan modal
            document.getElementById("lihatPerangkatModal").style.display = "flex";

            // Ambil data histori perangkat
            $.get(`/histori-perangkat/${id_perangkat}`, function(response) {
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
function closeLihatPerangkatModal() {
    document.getElementById("lihatPerangkatModal").style.display = "none";
}
</script>