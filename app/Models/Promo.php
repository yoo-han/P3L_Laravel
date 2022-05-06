<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_promo';
    protected $fillable = [
        'kode_promo',
        'jenis_promo',
        'potongan_promo',
        'keterangan_promo',
        'status_promo', 
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
