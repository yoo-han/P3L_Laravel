<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReservasiMobil extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'id_reservasi';
    protected $fillable = [
        'id_reservasi',
        'id_customer',
        'id_mobil', 
        'id_pegawai',
        'id_promo',
        'id_driver',
        'tanggal_transaksi',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_reservasi',
        'no_sim',
        'tarif_driver',
        'metode_pembayaran',
        'bukti_transfer',
        'total_pembayaran',
        'tanggal_kembali',
        'denda',
        'rating_driver',
        'status_reservasi',
    ];

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
