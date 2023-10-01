<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Carbon\Carbon;

class Karyawan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "karyawan";

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function getNamaJabatanAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->nama;
        }
    }

    public function getTargetPendapatanAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->target_pendapatan;
        }
    }

    public function getBonusTargetAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->bonus_target;
        }
    }

    public function getKomisiAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->komisi;
        }
    }

    public function getLemburAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->lembur;
        }
    }

    public function getTargetAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->target_pendapatan;
        }
    }

    public function hasUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUsernameAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->username;
        }
    }

    public function getEmailAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->email;
        }
    }

    public function hasToko()
    {
        return $this->belongsToMany(Toko::class, 'karyawans_tokos');
    }

    public function hasGudang()
    {
        return $this->belongsToMany(Gudang::class, 'karyawans_gudangs');
    }


    public function hasRoster()
    {
        return $this->hasMany(Roster::class, 'karyawan_id')->orderBy('tanggal');;
    }

    public function hasRosterToday()
    {
        return $this->hasOne(Roster::class, 'karyawan_id')->orderBy('tanggal')->whereDate('tanggal', now())->latest()->take(1);
    }

    public function getJamMasukAttribute()
    {
        if ($this->hasRosterToday) {
            return $this->hasRosterToday->jam_masuk;
        }
    }
    public function getJamPulangAttribute()
    {
        if ($this->hasRosterToday) {
            return $this->hasRosterToday->jam_pulang;
        }
    }

    public function getTotalGajiAttribute()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $denda = Absensi::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->sum('denda');
        $absensi = Absensi::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->whereStatus('pulang')->count();
        $lembur =  Absensi::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->where('status', 'like', '%pulang lembur%')->count();
        $total_komisi = 0;

        $total_komisi = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $this->id)->sum('komisi');

        $total_penjualan_all = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $this->id)->sum('harga_jual');

        $bonus = 0;

        if ($total_penjualan_all >= $this->target_pendapatan) {
            $bonus = $this->bonus_target;
        }
        $tipeGajih = $this->hasJabatan->gajih;
        $total_tip = 0;
        $total_tip = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $this->id)->sum('tip');

        $jumlahLembur = $lembur * $this->lembur;
        if ($tipeGajih == 'perbulan') {
            $totalGajih = $this->hasJabatan->gajih_perbulan + $total_tip  + ($jumlahLembur);
        } else {
            $totalGajih = $this->hasJabatan->gajih_perhari * $absensi  + ($jumlahLembur);
        }

        return (($totalGajih + $total_komisi + $bonus) - $denda);
    }

    public function getTotalGajiBulanLaluAttribute()
    {
        $start = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s');

        $denda = Absensi::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->sum('denda');
        $absensi = Absensi::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->whereStatus('pulang')->count();
        $lembur =  Absensi::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->where('status', 'like', '%pulang lembur%')->count();
        $total_komisi = 0;

        $total_komisi = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $this->id)->sum('komisi');

        $total_penjualan_all = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $this->id)->sum('harga_jual');

        $bonus = 0;

        if ($total_penjualan_all >= $this->target_pendapatan) {
            $bonus = $this->bonus_target;
        }
        $tipeGajih = $this->hasJabatan->gajih;
        $total_tip = 0;
        $total_tip = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $this->id)->sum('tip');

        $jumlahLembur = $lembur * $this->lembur;
        if ($tipeGajih == 'perbulan') {
            $totalGajih = $this->hasJabatan->gajih_perbulan + $total_tip  + ($jumlahLembur);
        } else {
            $totalGajih = $this->hasJabatan->gajih_perhari * $absensi  + ($jumlahLembur);
        }

        return (($totalGajih + $total_komisi + $bonus) - $denda);
    }

    public function getTotalKomisiPerhariIniAttribute()
    {
        $start = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        return  PenjualanDetail::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->get()->sum('komisi');
    }
    public function getTotalKomisiBulanIniAttribute()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        return  PenjualanDetail::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->get()->sum('komisi');
    }
    public function getTotalKomisiBulanLaluAttribute()
    {
        $start = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s');

        return  PenjualanDetail::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->get()->sum('komisi');
    }
    public function getTotalLayananPerhariIniAttribute()
    {
        $start = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        return  PenjualanDetail::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->get()->count('komisi');
    }

    public function getTotalPenjualanBulanLaluAttribute()
    {
        $start = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s');

        return  PenjualanDetail::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->sum('harga_jual');
    }

    public function getTotalPenjualanBulanIniAttribute()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        return  PenjualanDetail::where('karyawan_id', $this->id)->whereBetween('created_at', [$start, $end])->sum('harga_jual');
    }


    public function penjualan()
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
            'promosi_id'
        )->withTimestamps();
    }
}
