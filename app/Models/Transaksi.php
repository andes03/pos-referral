<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pelanggan',
        'id_pegawai',
        'total',
        'metode_pembayaran',
        'status_pembayaran',
        'tanggal_transaksi',
        'kode_referal'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total' => 'decimal:2'
    ];

    // Accessor untuk kode transaksi
    public function getKodeTransaksiAttribute()
    {
        // Format: ddmmyy-TRA001
        $tanggal = $this->tanggal_transaksi->format('dmY');
        $idFormatted = str_pad($this->id_transaksi, 3, '0', STR_PAD_LEFT);
        return "{$tanggal}-TRA{$idFormatted}";
    }

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }


}
