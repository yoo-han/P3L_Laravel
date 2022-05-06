<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MitraMobil extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_mitra';
    protected $fillable = [
        'nama_pemilik',
        'no_ktp_pemilik',
        'alamat_pemilik',
        'no_telp_pemilik',
        'periode_kontrak_mulai',
        'periode_kontrak_akhir',
            
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
