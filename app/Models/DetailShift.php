<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DetailShift extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detail_shift';
    protected $fillable = [
        'id_pegawai',
        'id_shift',
        'hari',
    ];

    public function getPegawai(){
        return $this->belongsTo(Pegawai::class,'id_pegawai','id_pegawai');
    }

    public function getShift(){
        return $this->belongsTo(Shift::class,'id_shift','id_shift');
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
