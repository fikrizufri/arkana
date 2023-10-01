<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Pembelian extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "pembelian";

    public function setNoNotaAttribute($value)
    {
        $this->attributes['no_nota'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasProduk()
    {
        return $this->belongsToMany(Produk::class, 'pembelian_produk')->withPivot(
            'qty',
            'harga_beli',
        );
    }

    public function getTanggalTampilAttribute()
    {
        if ($this->created_at) {
            return tanggal_indonesia($this->created_at);
        }
    }

    public function getTotalBeliAttribute()
    {
        $sum = 0;
        if ($this->hasProduk) {
            foreach ($this->hasProduk as $value) {
                $sum += $value->pivot->qty * $value->pivot->harga_beli;
            }
        }
        return $sum;
    }

    public function getTotalQtyAttribute()
    {
        $sum = 0;
        if ($this->hasProduk) {
            foreach ($this->hasProduk as $value) {
                $sum += $value->pivot->qty;
            }
        }
        return $sum;
    }
    public function hasSupplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getNamaSupplierAttribute()
    {
        if ($this->hasSupplier) {
            return $this->hasSupplier->nama;
        }
    }
}
