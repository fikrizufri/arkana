<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "penjualan_produk";
    public $incrementing = false;

    public function hasProduk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function getProdukAttribute()
    {
        if ($this->hasProduk) {
            return $this->hasProduk->nama;
        }
    }
    public function getNamaKategoriAttribute()
    {
        if ($this->hasProduk) {
            return $this->hasProduk->nama_kategori;
        }
    }
    public function getProdukKomisiAttribute()
    {
        if ($this->hasProduk) {
            return $this->hasProduk->komisi;
        }
    }

    public function hasKaryawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function getKaryawanAttribute()
    {
        if ($this->hasKaryawan) {
            return $this->hasKaryawan->nama;
        }
    }

    public function hasPenjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function getNoNotaAttribute()
    {
        if ($this->hasPenjualan) {
            return $this->hasPenjualan->no_nota;
        }
    }

    public function getPelangganAttribute()
    {
        if ($this->hasPenjualan) {
            return $this->hasPenjualan->pelanggan;
        }
    }

    public function getTanggalAttribute()
    {
        if ($this->hasPenjualan) {
            return $this->hasPenjualan->tanggal_tampil;
        }
    }
}
