<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mobil extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mobil';
    protected $fillable = [
        'id_mitra',
        'nama_mobil',
        'tipe_mobil',
        'jenis_transmisi',
        'jenis_bahan_bakar',
        'volume_bahan_bakar',
        'warna_mobil',
        'kapasitas_penumpang',
        'fasilitas',
        'kategori_aset',
        'plat_nomor',
        'nomor_stnk',
        'harga_sewa',
        'foto_mobil',
        'total_peminjaman',
        'tanggal_terakhir_servis',
        'status_mobil'
    ];

    public function getMitra(){
        return $this->belongsTo(MitraMobil::class,'id_mitra','id_mitra');
    }

    public function getCreatedAtAttribute()
    {
        if (!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }//convert format created_at menjadi Y-m-d H:i:s

    public function getUpdatedAtAttribute()
    {
        if (!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }//convert format updated_at menjadi Y-m-d H:i:s
}
