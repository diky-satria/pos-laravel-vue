<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = ['id_kategori','nama','kode','gambar','stok','harga'];

    public function kategori()
    {
        return $this->belongsTo('App\Models\Kategori', 'id_kategori', 'id');
    }
}
