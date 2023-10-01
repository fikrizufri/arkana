<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranKasBon extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "pembayaran_kas_bon";

    public function hasKasBon()
    {
        return $this->belongsTo(KasBon::class, 'kas_bon_id');
    }

    public function getNoNotaBonAttribute()
    {
        if ($this->hasKasBon) {
            return $this->hasKasBon->nota_kasbon;
        }
    }
    public function getKaryawanAttribute()
    {
        if ($this->hasKasBon) {
            return ucfirst($this->hasKasBon->nama_karyawan);
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
        $this->attributes['nota_pembayaran_kasbon'] = $noNota;
    }
}
