<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListAlatukur extends Model
{
    use HasFactory;
    protected $table = 'listalatukur';
    public $timestamps = false; // Disable automatic timestamps

    protected $primaryKey = 'id_alatukur'; // Tambahkan ini untuk memastikan primary key benar

    protected $fillable = [
        'id_alatukur',
        'kode_region',
        'kode_alatukur',
        'kode_brand',
        'type',
        'serialnumber',
        'alatukur_ke',
        'tahunperolehan',
        'kondisi',
        'keterangan',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function jenisalatukur()
    {
        return $this->belongsTo(JenisAlatukur::class, 'kode_alatukur', 'kode_alatukur');
    }

    public function brand()
    {
        return $this->belongsTo(BrandAlatukur::class, 'kode_brand', 'kode_brand');
    }

    protected static function boot()
    {
        parent::boot();

        // Saat penambahan data (create)
        static::created(function ($alatukur) {

            $maxid_alatukur = ListAlatukur::max('id_alatukur') ?? 0;
            $newid_alatukur = $maxid_alatukur + 1;
            
            HistoriAlatukur::create([
                'id_alatukur'     => $newid_alatukur-1,
                'kode_region'     => $alatukur->kode_region,
                'kode_alatukur'   => $alatukur->kode_alatukur,
                'alatukur_ke'     => $alatukur->alatukur_ke,
                'kode_brand'      => $alatukur->kode_brand,
                'type'            => $alatukur->type,
                'serialnumber'    => $alatukur->serialnumber,
                'tahunperolehan'  => $alatukur->tahunperolehan,
                'kondisi'         => $alatukur->kondisi,
                'keterangan'         => $alatukur->keterangan,
                'histori'      => 'Ditambahkan',
                'tanggal_perubahan' => now(),
            ]);
        });

        // Saat data diedit (update)
        static::updating(function ($alatukur) {
            // Hanya ambil atribut yang berubah
            $dirty    = $alatukur->getDirty();
            $original = $alatukur->getOriginal();
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
        
            HistoriAlatukur::create([
                'id_alatukur'    => $alatukur->id_alatukur,
                'kode_region'     => $alatukur->kode_region,
                'kode_alatukur'   => $alatukur->kode_alatukur,
                'alatukur_ke'     => $alatukur->alatukur_ke,
                'kode_brand'      => $alatukur->kode_brand,
                'type'            => $alatukur->type,
                'serialnumber'    => $alatukur->serialnumber,
                'tahunperolehan'  => $alatukur->tahunperolehan,
                'kondisi'         => $alatukur->kondisi,
                'keterangan'         => $alatukur->keterangan,
                'histori'      => 'Perubahan ' . $changesText,
                'tanggal_perubahan' => now(),
            ]);
        });
         

        // Saat data dihapus (delete)
        static::deleted(function ($alatukur) {
            HistoriAlatukur::create([
                'id_alatukur'    => $alatukur->id_alatukur,
                'kode_region'     => $alatukur->kode_region,
                'kode_alatukur'   => $alatukur->kode_alatukur,
                'alatukur_ke'     => $alatukur->alatukur_ke,
                'kode_brand'      => $alatukur->kode_brand,
                'type'            => $alatukur->type,
                'serialnumber'    => $alatukur->serialnumber,
                'tahunperolehan'  => $alatukur->tahunperolehan,
                'kondisi'         => $alatukur->kondisi,
                'keterangan'         => $alatukur->keterangan,
                'histori'      => 'Data alatukur dihapus',
                'tanggal_perubahan' => now(),
            ]);
        });
    }

    // Relasi ke model AlatukurHistory
    public function histories()
    {
        return $this->hasMany(HistoriAlatukur::class, 'id_alatukur');
    }
}
