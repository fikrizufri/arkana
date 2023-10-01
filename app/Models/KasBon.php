<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasBon extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "kas_bon";

    public function hasKaryawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function getNamaKaryawanAttribute()
    {
        if ($this->hasKaryawan) {
            return $this->hasKaryawan->nama;
        }
    }
    public function getTanggalTampilAttribute()
    {
        if ($this->created_at) {
            return tanggal_indonesia($this->created_at);
        }
    }

    public function setUangAttribute($value)
    {
        $this->attributes['uang'] = $value;
        $dataKasbon = Self::count();
        if ($dataKasbon >= 1) {
            $no = str_pad($dataKasbon + 1, 4, "0", STR_PAD_LEFT);
            $noNota =  $no . "/" . "KSB/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $noNota =  $no . "/" . "KSB/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }
        $this->attributes['nota_kasbon'] = $noNota;
    }
}
