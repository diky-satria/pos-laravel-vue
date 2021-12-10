<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangOut extends Model
{
    use HasFactory;

    protected $fillable = ['tanggal','id_petugas','id_barang','pengurangan','keterangan'];

    public function petugas()
    {
        return $this->belongsTo('App\Models\User', 'id_petugas', 'id');
    }

    public function barang()
    {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }
}
