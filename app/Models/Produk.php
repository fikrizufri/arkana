<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Carbon\Carbon;

class Produk extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "produk";
    // appends
    protected $appends = ["nama_kategori"];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function KategoriHargaJual()
    {
        return $this->belongsToMany(KategoriHargaJual::class, 'produk_kategori_harga')->withPivot('harga_jual');
    }

    public function getHargaJualAttribute()
    {
        if ($this->KategoriHargaJual) {
            if (count($this->KategoriHargaJual) != 0) {
                return $this->KategoriHargaJual()->orderBy('harga_jual', 'desc')->first()->pivot->harga_jual;
            }
        }
    }

    public function hasJenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    public function getJenisAttribute()
    {
        if ($this->hasJenis) {
            return $this->hasJenis->nama;
        }
    }
    public function getNamaKategoriAttribute()
    {
        if ($this->hasJenis) {
            return $this->hasJenis->nama_kategori;
        }
    }
    public function hasSatuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function getSatuanAttribute()
    {
        if ($this->hasSatuan) {
            return $this->hasSatuan->nama;
        }
    }


    public function hasPromosi()
    {
        return $this->belongsToMany(Promosi::class, 'promosi_produk')->withPivot('diskon', 'type_diskon');
    }

    public function hasBahan()
    {
        return $this->belongsToMany(Self::class, 'produk_bahan', 'produk_id', 'bahan_id')->withPivot('qty');
    }

    public function getTotalAttribute()
    {
        $start = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        $penjualandetail =  PenjualanDetail::where('produk_id', $this->id)->whereBetween('created_at', [$start, $end])->get();

        return count($penjualandetail);
    }
    public function getTotalPerbulanAttribute()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $penjualandetail =  PenjualanDetail::where('produk_id', $this->id)->whereBetween('created_at', [$start, $end])->get();

        return count($penjualandetail);
    }

    public function hasPenjualan()
    {
        return $this->belongsToMany(Penjualan::class, 'penjualan_produk')->withPivot(
            'type_diskon',
            'harga_beli',
            'harga_jual',
            'qty',
            'total_harga',
            'diskon_produk',
            'karyawan_id',
            'komisi',
            'promosi_id',
        )->withTimestamps();
    }

    public function hasKaryawan()
    {
        return $this->belongsToMany(Karyawan::class, 'penjualan_produk');
    }
}
