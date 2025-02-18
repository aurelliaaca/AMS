<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\HistoriPerangkat;

class ListPerangkat extends Model
{
    use HasFactory;
    protected $table = 'listperangkat';
    public $timestamps = false; // Disable automatic timestamps

    protected $primaryKey = 'id_perangkat'; // Tambahkan ini untuk memastikan primary key benar

    protected $fillable = [
        'id_perangkat',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_perangkat',
        'perangkat_ke',
        'kode_brand',
        'type',
        'uawal',
        'uakhir',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    public function jenisperangkat()
    {
        return $this->belongsTo(JenisPerangkat::class, 'kode_perangkat', 'kode_perangkat');
    }

    public function brand()
    {
        return $this->belongsTo(BrandPerangkat::class, 'kode_brand', 'kode_brand');
    }

    protected static function boot()
    {
        parent::boot();

        // Saat penambahan data (create)
        static::created(function ($perangkat) {

            $maxid_perangkat = ListPerangkat::max('id_perangkat') ?? 0;
            $newid_perangkat = $maxid_perangkat + 1;

            HistoriPerangkat::create([
                'id_perangkat'    => $newid_perangkat-1,
                'kode_region'     => $perangkat->kode_region,
                'kode_site'       => $perangkat->kode_site,
                'no_rack'         => $perangkat->no_rack,
                'kode_perangkat'  => $perangkat->kode_perangkat,
                'perangkat_ke'    => $perangkat->perangkat_ke,
                'kode_brand'      => $perangkat->kode_brand,
                'type'            => $perangkat->type,
                'uawal'           => $perangkat->uawal,
                'uakhir'          => $perangkat->uakhir,
                'keterangan'      => 'Ditambahkan',
                'tanggal_perubahan' => now(),
            ]);
        });

        // Saat data diedit (update)
        static::updating(function ($perangkat) {
            // Hanya ambil atribut yang berubah
            $dirty    = $perangkat->getDirty();
            $original = $perangkat->getOriginal();
            $changeTexts = [];
        
            // Bandingkan nilai lama dan baru untuk setiap atribut yang berubah
            foreach ($dirty as $key => $newValue) {
                // Pastikan nilai asli tersedia dan berbeda dengan nilai baru
                if (isset($original[$key]) && $newValue !== $original[$key]) {
                    $changeTexts[] = "{$key} dari [{$original[$key]}] menjadi [{$newValue}]";
                }
            }
        
            // Gabungkan semua keterangan perubahan dengan koma sebagai pemisah
            $changesText = implode(', ', $changeTexts);
        
            HistoriPerangkat::create([
                'id_perangkat'    => $perangkat->id_perangkat,
                'kode_region'     => $perangkat->kode_region,
                'kode_site'       => $perangkat->kode_site,
                'no_rack'         => $perangkat->no_rack,
                'kode_perangkat'  => $perangkat->kode_perangkat,
                'perangkat_ke'    => $perangkat->perangkat_ke,
                'kode_brand'      => $perangkat->kode_brand,
                'type'            => $perangkat->type,
                'uawal'           => $perangkat->uawal,
                'uakhir'          => $perangkat->uakhir,
                'keterangan'      => 'Perubahan ' . $changesText,
                'tanggal_perubahan' => now(),
            ]);
        });
         

        // Saat data dihapus (delete)
        static::deleted(function ($perangkat) {
            HistoriPerangkat::create([
                'id_perangkat'    => $perangkat->id_perangkat,
                'kode_region'     => $perangkat->kode_region,
                'kode_site'       => $perangkat->kode_site,
                'no_rack'         => $perangkat->no_rack,
                'kode_perangkat'  => $perangkat->kode_perangkat,
                'perangkat_ke'    => $perangkat->perangkat_ke,
                'kode_brand'      => $perangkat->kode_brand,
                'type'            => $perangkat->type,
                'uawal'           => $perangkat->uawal,
                'uakhir'          => $perangkat->uakhir,
                'keterangan'      => 'Data perangkat dihapus',
                'tanggal_perubahan' => now(),
            ]);
        });
    }

    // Relasi ke model PerangkatHistory
    public function histories()
    {
        return $this->hasMany(HistoriPerangkat::class, 'id_perangkat');
    }
}