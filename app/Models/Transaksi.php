<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = ['kode','tgl','id_pelanggan','id_petugas','status','total','tunai','kembalian'];

    public function transaksi_barangs()
    {
        return $this->belongsToMany('App\Models\Barang', 'transaksi_barangs', 'id_transaksi', 'id_barang')->withPivot(['id','qty']);
    }

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Pelanggan', 'id_pelanggan', 'id');
    }

    public function petugas()
    {
        return $this->belongsTo('App\Models\User', 'id_petugas', 'id');
    }
}
