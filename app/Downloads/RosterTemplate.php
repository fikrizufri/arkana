<?php

namespace App\Downloads;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class RosterTemplate implements FromView
{
    protected $bulan;

    function __construct($bulan)
    {
        $this->bulan = $bulan;
    }

    public function view(): View
    {
        $bulan = $this->bulan;
        $karyawan = Karyawan::orderBy('nama')->get();
        $tanggal = Carbon::now()->daysInMonth;

        return view('roster.download', compact(
            'karyawan',
            'tanggal',
            'bulan',
        ));
    }
}
