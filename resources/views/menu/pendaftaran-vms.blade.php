    <head>
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabelaset.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
    </head>
    
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="addPendaftaranModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddPendaftaranModal()">×</button>
        <h2>Form Kunjungan Data Center</h2>
        <form id="addPendaftaranForm" method="POST">
            @csrf
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="nama_pemohon">Nama Pemohon</label>
                        <input type="text" id="nama_pemohon" name="nama_pemohon" required>
                    </div>

                    <div class="form-group">
                        <label for="pengawas">Pengawas Lapangan</label>
                        <input type="text" id="pengawas" name="pengawas" required>
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="no_hp_pemohon">No HP Pemohon</label>
                        <input type="tel" id="no_hp_pemohon" name="no_hp_pemohon" required>
                    </div>

                    <div class="form-group">
                        <label for="no_hp_pengawas">No HP Pengawas</label>
                        <input type="tel" id="no_hp_pengawas" name="no_hp_pengawas" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="rekanan-section">
                    <label>Rekanan</label>
                    <div id="rekanan-container">
                        <div class="rekanan-item">
                            <input type="text" name="nama_rekanan[]" placeholder="Nama" required>
                            <input type="text" name="perusahaan_rekanan[]" placeholder="Nama Perusahaan" required>
                            <input type="text" name="ktp_rekanan[]" placeholder="No KTP" required>
                            <input type="tel" name="telp_rekanan[]" placeholder="No Telepon" required>
                            <button type="button" class="btn-remove-rekanan" style="display: none;">-</button>
                        </div>
                    </div>
                </div>
                <div class="button-container-rekanan">
                <button type="button" id="add-rekanan" class="add-button">+ Tambah Rekanan</button>
                </div>
                <div class="form-group">
                    <label for="divisi">Divisi</label>
                    <input type="text" id="divisi" name="divisi" required>
                </div>
            </div>

            <div class="form-container">
                <div class="left-column">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi Pengerjaan</label>
                        <input type="text" id="lokasi" name="lokasi" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_pekerjaan">Jenis Pekerjaan</label>
                        <select id="jenis_pekerjaan" name="jenis_pekerjaan" required>
                            <option value="">Pilih Jenis Pekerjaan</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="checking">Checking</option>
                            <option value="installation">Installation</option>
                            <option value="dismantle">Dismantle</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="form-group" id="keterangan_others" style="display: none;">
                        <label for="keterangan">Keterangan Others</label>
                        <input type="text" id="keterangan" name="keterangan">
                    </div>
                </div>

                <div class="right-column">

                    <div class="form-row">
                    <div class="form-group">
                            <label for="waktu_mulai">Waktu Mulai</label>
                            <input type="time" id="waktu_mulai" name="waktu_mulai" required>
                        </div>
                        <div class="form-group">
                            <label for="waktu_selesai">Waktu Selesai</label>
                            <input type="time" id="waktu_selesai" name="waktu_selesai" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_rack">Nomor Rack</label>
                        <input type="text" id="no_rack" name="no_rack" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Pekerjaan</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="add-button">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rekananContainer = document.getElementById('rekanan-container');
    const addRekananBtn = document.getElementById('add-rekanan');
    const jenisPekerjaanSelect = document.getElementById('jenis_pekerjaan');
    const keteranganOthers = document.getElementById('keterangan_others');

    addRekananBtn.addEventListener('click', function() {
        const newRekanan = document.createElement('div');
        newRekanan.className = 'rekanan-item';
        newRekanan.innerHTML = `
            <input type="text" name="nama_rekanan[]" placeholder="Nama" required>
            <input type="text" name="perusahaan_rekanan[]" placeholder="Nama Perusahaan" required>
            <input type="text" name="ktp_rekanan[]" placeholder="No KTP" required>
            <input type="tel" name="telp_rekanan[]" placeholder="No Telepon" required>
            <button type="button" class="btn-remove-rekanan">-</button>
        `;
        rekananContainer.appendChild(newRekanan);

        // Show remove buttons if there's more than one rekanan
        const removeButtons = document.querySelectorAll('.btn-remove-rekanan');
        removeButtons.forEach(button => button.style.display = 'block');
    });

    rekananContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-rekanan')) {
            e.target.parentElement.remove();
            
            // Hide remove button if only one rekanan remains
            const removeButtons = document.querySelectorAll('.btn-remove-rekanan');
            if (removeButtons.length === 1) {
                removeButtons[0].style.display = 'none';
            }
        }
    });

    jenisPekerjaanSelect.addEventListener('change', function() {
        keteranganOthers.style.display = this.value === 'others' ? 'block' : 'none';
        if (this.value !== 'others') {
            document.getElementById('keterangan').value = '';
        }
    });
});

function openAddPendaftaranModal() {
    // Reset form
    document.getElementById('addPendaftaranForm').reset();
    
    // Reset rekanan to single entry
    const rekananContainer = document.getElementById('rekanan-container');
    rekananContainer.innerHTML = `
        <div class="rekanan-item">
            <input type="text" name="nama_rekanan[]" placeholder="Nama" required>
            <input type="text" name="perusahaan_rekanan[]" placeholder="Nama Perusahaan" required>
            <input type="text" name="ktp_rekanan[]" placeholder="No KTP" required>
            <input type="tel" name="telp_rekanan[]" placeholder="No Telepon" required>
            <button type="button" class="btn-remove-rekanan" style="display: none;">-</button>
        </div>
    `;
    
    // Reset and hide others field
    const keteranganOthers = document.getElementById('keterangan_others');
    if (keteranganOthers) {
        keteranganOthers.style.display = 'none';
    }
    
    // Show modal with flex display
    const modal = document.getElementById('addPendaftaranModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeAddPendaftaranModal() {
    const modal = document.getElementById('addPendaftaranModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Add event listener for ESC key to close modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddPendaftaranModal();
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('addPendaftaranModal');
    if (event.target === modal) {
        closeAddPendaftaranModal();
    }
});
</script> 