<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="addAlatukurModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddAlatukurModal()">×</button>
        <h2>Tambah Alatukur Baru</h2>
        <form id="addAlatukurForm" method="POST">
            @csrf
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="regionAdd">Region</label>
                        <select id="regionAdd" name="kode_region" required>
                            <option value="">Pilih Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="alatukurAdd">Alatukur</label>
                        <select id="alatukurAdd" name="kode_alatukur" required>
                            <option value="">Pilih Alatukur</option>
                            @foreach($listpkt as $p)
                                <option value="{{ $p->kode_alatukur }}">{{ $p->nama_alatukur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="brandAdd">Brand</label>
                        <select id="brandAdd" name="kode_brand">
                            <option value="">Pilih Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->kode_brand }}">{{ $brand->nama_brand }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <input type="text" id="type" name="type">
                    </div>
                    <div class="form-group">
                        <label for="serialnumber">Serial Number</label>
                        <input type="text" id="serialnumber" name="serialnumber">
                    </div>
                </div>
                <div class="right-column">
                    <div class="form-group">
                        <label for="tahunperolehan">Tahun Perolehan</label>
                        <select id="tahunperolehan" name="tahunperolehan" class="form-control">
                            <option value="">Pilih Tahun</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kondisi">Kondisi</label>
                        <input type="text" id="kondisi" name="kondisi">
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan">
                    </div>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const selectTahun = document.getElementById('tahunperolehan');
    const currentYear = new Date().getFullYear();
    for (let i = currentYear; i >= 2000; i--) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        selectTahun.appendChild(option);
    }
    
    $('#addAlatukurForm').on('submit', function(e) {
        e.preventDefault();
        closeAddAlatukurModal(); // Ini akan menutup modal sebelum SweetAlert muncul
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menambahkan data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/store-alatukur',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                closeAddAlatukurModal();
                                LoadData();
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                });
            }
        });
    });
});
function openAddAlatukurModal() {
    $('#addAlatukurForm')[0].reset();
    document.getElementById("addAlatukurModal").style.display = "flex";
}
function closeAddAlatukurModal() {
    document.getElementById("addAlatukurModal").style.display = "none";
}
</script>
