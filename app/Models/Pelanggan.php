<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Pelanggan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "pelanggan";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasPenjualan()
    {
        return $this->hasMany(Penjualan::class, 'pelanggan_id');
    }
}
