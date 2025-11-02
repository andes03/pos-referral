<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoinHistori extends Model
{
    use HasFactory;

    protected $table = 'poin_histori';
    protected $primaryKey = 'id_histori';

    protected $fillable = [
        'id_pelanggan',
        'jenis',
        'jumlah_poin',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'jumlah_poin' => 'integer',
        'tanggal' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function isTambah()
    {
        return $this->jenis === 'tambah';
    }

    public function isKurang()
    {
        return $this->jenis === 'kurang';
    }
}
