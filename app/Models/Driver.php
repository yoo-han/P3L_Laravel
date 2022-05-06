<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    public $incrementing = false;
    protected $primaryKey = 'id_driver';
    protected $fillable = [
        'id_driver',
        'nama_driver',
        'alamat_driver',
        'tanggal_lahir_driver',
        'jenis_kelamin_driver',
        'email_driver',
        'password_driver',
        'no_telp_driver',
        'bahasa',
        'foto_driver',
        'sim_driver',
        'surat_bebas_napza',
        'surat_kesehatan_jasmani',
        'surat_kesehatan_jiwa',
        'skck',
        'status_driver',
        'rerata_rating',
        'banyak_rating',
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
