<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Penjualan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "penjualan";
    protected $appends = ["bank"];
    public function setNoNotaAttribute($value)
    {
        $this->attributes['no_nota'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasProduk()
    {
        return $this->belongsToMany(Produk::class, 'penjualan_produk')->withPivot(
            'type_diskon',
            'harga_beli',
            'harga_jual',
            'qty',
            'total_harga',
            'diskon_produk',
            'karyawan_id',
            'komisi',
            'tip',
            'promosi_id'
        )->withTimestamps();
    }

    public function getTanggalTampilAttribute()
    {
        if ($this->created_at) {
            return tanggal_indonesia($this->created_at);
        }
    }

    public function hasPelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function hasBank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function hasKaryawan()
    {
        return $this->belongsToMany(Karyawan::class, 'penjualan_produk');
    }

    public function getPelangganAttribute()
    {
        if ($this->hasPelanggan) {
            return $this->hasPelanggan->nama;
        }
    }

    public function getBankAttribute()
    {
        if ($this->hasBank) {
            return $this->hasBank->nama;
        }
    }
}
