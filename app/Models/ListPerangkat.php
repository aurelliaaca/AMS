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

        // Trigger untuk CREATE
        static::created(function ($perangkat) {
            try {
                \Log::info('Creating history for new perangkat: ' . $perangkat->id_perangkat);
                
                // Reload perangkat dengan relasi
                $perangkat->load(['region', 'site', 'perangkat', 'brand']);

                // Debug log
                \Log::info('Data for history:', [
                    'id_perangkat' => $perangkat->id_perangkat,
                    'kode_perangkat' => $perangkat->kode_perangkat,
                    'perangkat_data' => $perangkat->toArray()
                ]);

                $maxid_perangkat = ListPerangkat::max('id_perangkat') ?? 0;
                $newid_perangkat = $maxid_perangkat;

                HistoriPerangkat::create([
                    'idHiPe' => $newid_perangkat,
                    'region' => $perangkat->region ? $perangkat->region->nama_region : '-',
                    'site' => $perangkat->site ? $perangkat->site->nama_site : '-',
                    'nama_perangkat' => $perangkat->jenisperangkat ? $perangkat->jenisperangkat->nama_perangkat : '-',
                    'brand' => $perangkat->brand ? $perangkat->brand->nama_brand : '-',
                    'type' => $perangkat->type ?? '-',
                    'no_rack' => $perangkat->no_rack ?? '-',
                    'uawal' => $perangkat->uawal ?? '-',
                    'uakhir' => $perangkat->uakhir ?? '-',
                    'aksi' => 'ditambah',
                    'tanggal_perubahan' => now()
                ]);
                
                \Log::info('History created successfully');
            } catch (\Exception $e) {
                \Log::error('Error creating history: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
            }
        });

        // Trigger untuk UPDATE
        static::updated(function ($perangkat) {
            try {
                // Reload perangkat dengan relasi
                $perangkat->load(['region', 'site', 'perangkat', 'brand']);

                HistoriPerangkat::create([
                    'idHiPe' => $perangkat->id_perangkat,
                    'region' => $perangkat->region ? $perangkat->region->nama_region : '-',
                    'site' => $perangkat->site ? $perangkat->site->nama_site : '-',
                    'nama_perangkat' => $perangkat->jenisperangkat ? $perangkat->jenisperangkat->nama_perangkat : '-',
                    'brand' => $perangkat->brand ? $perangkat->brand->nama_brand : '-',
                    'type' => $perangkat->type ?? '-',
                    'no_rack' => $perangkat->no_rack ?? '-',
                    'uawal' => $perangkat->uawal ?? '-',
                    'uakhir' => $perangkat->uakhir ?? '-',
                    'aksi' => 'diedit',
                    'tanggal_perubahan' => now()
                ]);
                
                \Log::info('History updated successfully');
            } catch (\Exception $e) {
                \Log::error('Error updating history: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
            }
        });

        // Trigger untuk DELETE
        static::deleted(function ($perangkat) {
            try {
                \Log::info('Creating history for deleted perangkat: ' . $perangkat->id_perangkat);
                
                // Untuk delete, kita gunakan data yang sudah ada di model
                $regionName = $perangkat->region ? $perangkat->region->nama_region : '-';
                $siteName = $perangkat->site ? $perangkat->site->nama_site : '-';
                $perangkatName = $perangkat->jenisperangkat ? $perangkat->jenisperangkat->nama_perangkat : '-';
                $brandName = $perangkat->brand ? $perangkat->brand->nama_brand : '-';

                HistoriPerangkat::create([
                    'idHiPe' => $perangkat->id_perangkat,
                    'region' => $regionName,
                    'site' => $siteName,
                    'nama_perangkat' => $perangkatName,
                    'brand' => $brandName,
                    'type' => $perangkat->type ?? '-',
                    'no_rack' => $perangkat->no_rack ?? '-',
                    'uawal' => $perangkat->uawal ?? '-',
                    'uakhir' => $perangkat->uakhir ?? '-',
                    'aksi' => 'dihapus',
                    'tanggal_perubahan' => now()
                ]);
                
                \Log::info('History for deletion created successfully');
            } catch (\Exception $e) {
                \Log::error('Error creating delete history: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
            }
        });
    }
}
