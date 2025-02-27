<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HistoriJaringan;
use App\Models\Tipe;

class ListJaringan extends Model
{
    use HasFactory;

    protected $table = 'list_jaringan';
    protected $primaryKey = 'id_jaringan';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'RO',
        'tipe_jaringan',
        'segmen',
        'jartatup_jartaplok',
        'mainlink_backuplink',
        'panjang',
        'panjang_drawing',
        'jumlah_core',
        'jenis_kabel',
        'tipe_kabel',
        'status',
        'ket',
        'ket2',
        'kode_site_insan',
        'update',
        'route',
        'dci_eqx',
    ];

    public function tipe()
    {
        return $this->belongsTo(Tipe::class, 'tipe_jaringan', 'kode_tipe');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'RO', 'kode_region')->select(['kode_region', 'nama_region']);
    }
    

    public function historiJaringan()
    {
        return $this->hasMany(HistoriJaringan::class, 'idHiJar', 'id_jaringan');
    }

    protected static function boot()
    {
        parent::boot();

        // Trigger untuk CREATE
        static::created(function ($jaringan) {
            \Log::info('Trigger created called for jaringan: ' . $jaringan->id_jaringan);
            try {
                $jaringan->load('region', 'tipe');

                HistoriJaringan::create([
                    'idHiJar' => $jaringan->id_jaringan,
                    'RO' => $jaringan->region ? $jaringan->region->nama_region : '-',
                    'tipe_jaringan' => $jaringan->tipe ? $jaringan->tipe->nama_tipe : '-',
                    'segmen' => $jaringan->segmen ?? '-',
                    'jartatup_jartaplok' => $jaringan->jartatup_jartaplok ?? '-',
                    'mainlink_backuplink' => $jaringan->mainlink_backuplink ?? '-',
                    'panjang' => $jaringan->panjang ?? '-',
                    'panjang_drawing' => $jaringan->panjang_drawing ?? '-',
                    'jumlah_core' => $jaringan->jumlah_core ?? '-',
                    'jenis_kabel' => $jaringan->jenis_kabel ?? '-',
                    'tipe_kabel' => $jaringan->tipe_kabel ?? '-',
                    'status' => $jaringan->status ?? '-',
                    'ket' => $jaringan->ket ?? '-',
                    'ket2' => $jaringan->ket2 ?? '-',
                    'kode_site_insan' => $jaringan->kode_site_insan ?? '-',
                    'update' => $jaringan->update ?? '-',
                    'route' => $jaringan->route ?? '-',
                    'dci_eqx' => $jaringan->dci_eqx ?? '-',
                    'aksi' => 'ditambah',
                    'tanggal_perubahan' => now()
                ]);
            } catch (\Exception $e) {
                \Log::error('Error creating history: ' . $e->getMessage());
            }
        });

        // Trigger untuk UPDATE
        static::updated(function ($jaringan) {
            if ($jaringan->isDirty()) { // Pastikan ada perubahan
                \Log::info('Trigger updated called for jaringan: ' . $jaringan->id_jaringan);
        
                try {
                    $jaringan->load('region', 'tipe');
        
                    HistoriJaringan::create([
                        'idHiJar' => $jaringan->id_jaringan,
                        'RO' => $jaringan->region ? $jaringan->region->nama_region : '-',
                        'tipe_jaringan' => $jaringan->tipe ? $jaringan->tipe->nama_tipe : '-',
                        'segmen' => $jaringan->segmen ?? '-',
                        'jartatup_jartaplok' => $jaringan->jartatup_jartaplok ?? '-',
                        'mainlink_backuplink' => $jaringan->mainlink_backuplink ?? '-',
                        'panjang' => $jaringan->panjang ?? '-',
                        'panjang_drawing' => $jaringan->panjang_drawing ?? '-',
                        'jumlah_core' => $jaringan->jumlah_core ?? '-',
                        'jenis_kabel' => $jaringan->jenis_kabel ?? '-',
                        'tipe_kabel' => $jaringan->tipe_kabel ?? '-',
                        'status' => $jaringan->status ?? '-',
                        'ket' => $jaringan->ket ?? '-',
                        'ket2' => $jaringan->ket2 ?? '-',
                        'kode_site_insan' => $jaringan->kode_site_insan ?? '-',
                        'update' => $jaringan->update ?? '-',
                        'route' => $jaringan->route ?? '-',
                        'dci_eqx' => $jaringan->dci_eqx ?? '-',
                        'aksi' => 'diedit',
                        'tanggal_perubahan' => now()
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error updating history: ' . $e->getMessage());
                }
            }
        });
        

        // Trigger untuk DELETE
        static::deleting(function ($jaringan) {
            \Log::info('Trigger deleting called for jaringan: ' . $jaringan->id_jaringan);
            
            // Pastikan relasi sudah termuat
            $jaringan->loadMissing('region', 'tipe');
            
            try {
                HistoriJaringan::create([
                    'idHiJar' => $jaringan->id_jaringan,
                    'RO' => $jaringan->region ? $jaringan->region->nama_region : '-',
                    'tipe_jaringan' => $jaringan->tipe ? $jaringan->tipe->nama_tipe : '-',
                    'segmen' => $jaringan->segmen ?? '-',
                    'jartatup_jartaplok' => $jaringan->jartatup_jartaplok ?? '-',
                    'mainlink_backuplink' => $jaringan->mainlink_backuplink ?? '-',
                    'panjang' => $jaringan->panjang ?? '-',
                    'panjang_drawing' => $jaringan->panjang_drawing ?? '-',
                    'jumlah_core' => $jaringan->jumlah_core ?? '-',
                    'jenis_kabel' => $jaringan->jenis_kabel ?? '-',
                    'tipe_kabel' => $jaringan->tipe_kabel ?? '-',
                    'status' => $jaringan->status ?? '-',
                    'ket' => $jaringan->ket ?? '-',
                    'ket2' => $jaringan->ket2 ?? '-',
                    'kode_site_insan' => $jaringan->kode_site_insan ?? '-',
                    'update' => $jaringan->update ?? '-',
                    'route' => $jaringan->route ?? '-',
                    'dci_eqx' => $jaringan->dci_eqx ?? '-',
                    'aksi' => 'dihapus',
                    'tanggal_perubahan' => now()
                ]);
            
                \Log::info('History for deletion created successfully');
            } catch (\Exception $e) {
                \Log::error('Error creating delete history: ' . $e->getMessage());
            }
        });
    }
}
