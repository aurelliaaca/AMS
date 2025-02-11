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

    protected $primaryKey = 'WDM'; // Tambahkan ini untuk memastikan primary key benar

    protected $fillable = [
        'WDM',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_pkt',
        'pkt_ke',
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

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'kode_pkt', 'kode_pkt');
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
                \Log::info('Creating history for new perangkat: ' . $perangkat->WDM);
                
                // Reload perangkat dengan relasi
                $perangkat->load(['region', 'site', 'perangkat', 'brand']);

                // Debug log
                \Log::info('Data for history:', [
                    'WDM' => $perangkat->WDM,
                    'kode_pkt' => $perangkat->kode_pkt,
                    'perangkat_data' => $perangkat->toArray()
                ]);

                HistoriPerangkat::create([
                    'idHiPe' => $perangkat->WDM,
                    'region' => $perangkat->region ? $perangkat->region->nama_region : '-',
                    'site' => $perangkat->site ? $perangkat->site->nama_site : '-',
                    'nama_perangkat' => $perangkat->perangkat ? $perangkat->perangkat->nama_pkt : '-',
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
                    'idHiPe' => $perangkat->WDM,
                    'region' => $perangkat->region ? $perangkat->region->nama_region : '-',
                    'site' => $perangkat->site ? $perangkat->site->nama_site : '-',
                    'nama_perangkat' => $perangkat->perangkat ? $perangkat->perangkat->nama_pkt : '-',
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
                \Log::info('Creating history for deleted perangkat: ' . $perangkat->WDM);
                
                // Untuk delete, kita gunakan data yang sudah ada di model
                $regionName = $perangkat->region ? $perangkat->region->nama_region : '-';
                $siteName = $perangkat->site ? $perangkat->site->nama_site : '-';
                $perangkatName = $perangkat->perangkat ? $perangkat->perangkat->nama_pkt : '-';
                $brandName = $perangkat->brand ? $perangkat->brand->nama_brand : '-';

                HistoriPerangkat::create([
                    'idHiPe' => $perangkat->WDM,
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
