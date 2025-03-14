<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="addRackModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddRackModal()">×</button>
        <h2>Tambah Rack Baru</h2>
        <form id="addRackForm" method="POST">
            @csrf
            <input type="hidden" id="id_region" name="id_region">
            <input type="hidden" id="kode_region" name="kode_region">
            <div class="form-container">
                <div class="form-group">
                    <label for="kode_site">Kode Site</label>
                    <input type="text" id="kode_site" name="kode_site" required>
                </div>
                <div class="form-group">
                    <label for="no_rack">No Rack</label>
                    <input type="number" id="no_rack" name="no_rack" required>
                </div>
                <div class="form-group">
                    <label for="u">Jumlah U</label>
                    <input type="number" id="u" name="u" required>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('addRackForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman

        let formData = new FormData(this);

        fetch("{{ route('rack.store') }}", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                Swal.fire("Berhasil!", data.message, "success");
                closeAddRackModal();
            } else {
                Swal.fire("Gagal!", data.message, "error");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire("Gagal!", "Terjadi kesalahan, coba lagi.", "error");
        });
    });

    
    function openAddRackModal(regionName) {
    // Set the region name in the modal
    document.getElementById('id_region').value = regionName; 
    document.getElementById('kode_region').value = regionName; 

    // Display the modal
    document.getElementById('addRackModal').style.display = "block";
}

// Function to close the Add Rack modal
function closeAddRackModal() {
    document.getElementById('addRackModal').style.display = "none";
}
</script>
