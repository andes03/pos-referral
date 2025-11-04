<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Pelanggan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telp',
        'alamat',
        'kode_referal',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pelanggan) {
            if (empty($pelanggan->kode_referal)) {
                $pelanggan->kode_referal = self::generateUniqueReferralCode();
            }
        });
    }

    public static function generateUniqueReferralCode()
    {
        do {
            $code = 'REF' . strtoupper(Str::random(7));
        } while (self::where('kode_referal', $code)->exists());

        return $code;
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }
}
