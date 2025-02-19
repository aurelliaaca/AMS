<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListFasilitas extends Model
{
    use HasFactory;
    protected $table = 'listfasilitas';
    public $timestamps = false; // Disable automatic timestamps

    protected $primaryKey = 'id_fasilitas'; // Tambahkan ini untuk memastikan primary key benar

    protected $fillable = [
        'id_fasilitas',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_fasilitas',
        'fasilitas_ke',
        'kode_brand',
        'type',
        'serialnumber',
        'jml_fasilitas',
        'status',
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

    public function jenisfasilitas()
    {
        return $this->belongsTo(JenisFasilitas::class, 'kode_fasilitas', 'kode_fasilitas');
    }

    public function brand()
    {
        return $this->belongsTo(BrandFasilitas::class, 'kode_brand', 'kode_brand');
    }

    protected static function boot()
    {
        parent::boot();

        // Saat penambahan data (create)
        static::created(function ($fasilitas) {

            $maxid_fasilitas = ListFasilitas::max('id_fasilitas') ?? 0;
            $newid_fasilitas = $maxid_fasilitas + 1;

            HistoriFasilitas::create([
                'id_fasilitas'    => $newid_fasilitas-1,
                'kode_region'     => $fasilitas->kode_region,
                'kode_site'       => $fasilitas->kode_site,
                'no_rack'         => $fasilitas->no_rack,
                'kode_fasilitas'  => $fasilitas->kode_fasilitas,
                'fasilitas_ke'    => $fasilitas->fasilitas_ke,
                'kode_brand'      => $fasilitas->kode_brand,
                'type'            => $fasilitas->type,
                'serialnumber'    => $fasilitas->serialnumber,
                'jml_fasilitas'   => $fasilitas->jml_fasilitas,
                'status'          => $fasilitas->status,
                'uawal'          => $fasilitas->uawal,
                'uakhir'          => $fasilitas->uakhir,
                'histori'      => 'Ditambahkan',
                'tanggal_perubahan' => now(),
            ]);
        });

        // Saat data diedit (update)
        static::updating(function ($fasilitas) {
            // Hanya ambil atribut yang berubah
            $dirty    = $fasilitas->getDirty();
            $original = $fasilitas->getOriginal();
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
        
            HistoriFasilitas::create([
                'id_fasilitas'    => $fasilitas->id_fasilitas,
                'kode_region'     => $fasilitas->kode_region,
                'kode_site'       => $fasilitas->kode_site,
                'no_rack'         => $fasilitas->no_rack,
                'kode_fasilitas'  => $fasilitas->kode_fasilitas,
                'fasilitas_ke'    => $fasilitas->fasilitas_ke,
                'kode_brand'      => $fasilitas->kode_brand,
                'type'            => $fasilitas->type,
                'serialnumber'    => $fasilitas->serialnumber,
                'jml_fasilitas'   => $fasilitas->jml_fasilitas,
                'status'          => $fasilitas->status,
                'uawal'          => $fasilitas->uawal,
                'uakhir'          => $fasilitas->uakhir,
                'histori'      => 'Perubahan ' . $changesText,
                'tanggal_perubahan' => now(),
            ]);
        });
         

        // Saat data dihapus (delete)
        static::deleted(function ($fasilitas) {
            HistoriFasilitas::create([
                'id_fasilitas'    => $fasilitas->id_fasilitas,
                'kode_region'     => $fasilitas->kode_region,
                'kode_site'       => $fasilitas->kode_site,
                'no_rack'         => $fasilitas->no_rack,
                'kode_fasilitas'  => $fasilitas->kode_fasilitas,
                'fasilitas_ke'    => $fasilitas->fasilitas_ke,
                'kode_brand'      => $fasilitas->kode_brand,
                'type'            => $fasilitas->type,
                'serialnumber'    => $fasilitas->serialnumber,
                'jml_fasilitas'   => $fasilitas->jml_fasilitas,
                'status'          => $fasilitas->status,
                'uawal'          => $fasilitas->uawal,
                'uakhir'          => $fasilitas->uakhir,
                'histori'      => 'Data fasilitas dihapus',
                'tanggal_perubahan' => now(),
            ]);
        });
    }

    // Relasi ke model FasilitasHistory
    public function histories()
    {
        return $this->hasMany(HistoriFasilitas::class, 'id_fasilitas');
    }
}
