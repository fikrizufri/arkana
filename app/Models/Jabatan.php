<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Jabatan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "jabatan";

    public function hasToko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function getTokoAttribute()
    {
        if ($this->hasToko) {
            return $this->hasToko->nama;
        }
    }

    public function getGajiAttribute(Type $var = null)
    {
        if ($this->gajih == 'perbulan') {
            return $this->gajih_perbulan;
        } else {
            return $this->gajih_perhari;
        }
    }

    public function hasShift()
    {
        return $this->belongsToMany(Shift::class, 'shifts_jabatans')->withPivot('denda', 'menit');
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
