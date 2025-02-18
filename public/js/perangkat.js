document.addEventListener('DOMContentLoaded', function() {
    const importModal = new bootstrap.Modal(document.getElementById('importModal'));
    
    // Untuk menampilkan modal
    document.getElementById('btnImport').addEventListener('click', function() {
        importModal.show();
    });
}); 