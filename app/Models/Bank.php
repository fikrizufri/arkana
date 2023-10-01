<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Bank extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "bank";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasMetodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }

    public function getMetodePembayaranAttribute()
    {
        if ($this->hasMetodePembayaran) {
            return $this->hasMetodePembayaran->nama;
        }
    }
}
