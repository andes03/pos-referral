<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'nama',
        'harga',
        'stok',
        'deskripsi',
        'image',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    protected $with = ['kategori'];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }

    public function isAvailable()
    {
        return $this->stok > 0;
    }

    public function reduceStock($quantity)
    {
        if ($this->stok >= $quantity) {
            $this->decrement('stok', $quantity);
            return true;
        }
        return false;
    }

    public function addStock($quantity)
    {
        $this->increment('stok', $quantity);
    }
}
