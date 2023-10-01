<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Carbon\Carbon;

class Promosi extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "promosi";
    protected $casts = [
        'hari' => 'array',
    ];
    protected $date = [
        'tangal_mulai',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasProduk()
    {
        return $this->belongsToMany(Produk::class, 'promosi_produk')->withPivot('diskon', 'type_diskon');
    }

    public function getDiskonTampilAttribute()
    {
        if ($this->jenis_diskon == "nota") {
            if ($this->type_diskon == "persen") {
                return $this->diskon . " % ";
            }
            if ($this->type_diskon == "nominal") {
                return "Rp. " . format_uang($this->diskon);
            }
        } else {
            if ($this->produk == "semua") {
                if ($this->type_diskon == "persen") {
                    return $this->diskon . " % ";
                }
                if ($this->type_diskon == "nominal") {
                    return "Rp. " . format_uang($this->diskon);
                }
            } else {

                $listProduk = [];
                if ($this->hasProduk()) {
                    foreach ($this->hasProduk as $key => $value) {
                        if ($value->pivot->type_diskon == "persen") {
                            $listProduk[$key] = $value->nama . " | Diskon : " . $value->pivot->diskon . " % ";
                        }
                        if ($value->pivot->type_diskon == "nominal") {
                            $listProduk[$key] = $value->nama . " | Diskon : Rp. " . format_uang($this->diskon);
                        }
                    }
                    $str =  implode(" ", $listProduk);
                    return rtrim($str, ',');
                }
            }
        }
    }

    public function getProdukDiskonTampilAttribute()
    {
        $listProduk = [];
        if ($this->hasProduk()) {
            foreach ($this->hasProduk as $key => $value) {
                if ($value->pivot->type_diskon == "persen") {
                    $listProduk[$key] = $value->nama . " | Diskon : " . $value->pivot->diskon . " % ";
                }
                if ($value->pivot->type_diskon == "nominal") {
                    $listProduk[$key] = $value->nama . " | Diskon : Rp. " . format_uang($this->diskon);
                }
            }
            $str =  implode(" ", $listProduk);
            return rtrim($str, ',');
        }
    }

    public function getTypeDiskonTampilAttribute()
    {
        return ucfirst($this->type_diskon);
    }
    public function getJenisDiskonTampilAttribute()
    {
        return ucfirst($this->jenis_diskon);
    }
    public function getProdukTampilAttribute()
    {
        return ucfirst($this->produk);
    }
    public function getTanggalAttribute()
    {
        // 01/5/2022 00:00:00 - 31/5/2022 23:59:59
        $tanggal_mulai = Carbon::parse($this->tanggal_mulai)->format('d/m/Y');
        $tanggal_selesai = Carbon::parse($this->tanggal_selesai)->format('d/m/Y');
        $jam_mulai = $this->jam_mulai;
        $jam_selesai = $this->jam_selesai;

        $tanggal =  $tanggal_mulai . " " . $jam_mulai . " - " . $tanggal_selesai . " " . $jam_selesai;
        return $tanggal;
    }
}
