<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\KasBon;
use App\Models\PenjualanDetail;
use Carbon\Carbon;

class PenggajianController extends Controller
{

    public function __construct()
    {
        $this->route = 'penggajian';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function index()
    {
        $title = "Penggajian";
        $limit = 30;

        $query = Karyawan::query();

        $karyawans = $query->orderBy('nama', 'Asc')->paginate($limit);
        $count_karyawan = $query->count();
        $no = $limit * ($karyawans->currentPage() - 1);
        $now = Carbon::now()->subMonth(1);
        $now = bulan_indonesia($now);
        return view('penggajian.index', compact('title', 'karyawans', 'count_karyawan', 'no', 'now'));
    }

    public function show($slug)
    {
        return $this->pengajian($slug);
    }

    public function detail()
    {
        $slug = auth()->user()->karyawan->slug;
        return $this->pengajian($slug);
    }

    public function pengajian($slug)
    {
        $title = "Penggajian";
        $no = 0;



        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        $requestdate = request()->date;
        if (request()->date != '') {
            $date = explode(' - ', request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }
        $karyawan = Karyawan::where('slug', $slug)->first();


        $penggajian = Absensi::where('karyawan_id', $karyawan->id)->whereBetween('created_at', [$start, $end])->get();

        $penjualanDetail = PenjualanDetail::where('karyawan_id', $karyawan->id)->whereBetween('created_at', [$start, $end])->get();

        $denda = Absensi::where('karyawan_id', $karyawan->id)->whereBetween('created_at', [$start, $end])->sum('denda');
        $absensi = Absensi::where('karyawan_id', $karyawan->id)->whereBetween('created_at', [$start, $end])->whereStatus('pulang')->count();
        $lembur =  Absensi::where('karyawan_id', $karyawan->id)->whereBetween('created_at', [$start, $end])->where('status', 'like', '%pulang lembur%')->count();

        $total_komisi = 0;
        $bonus = 0;
        $total_penjualan_all = 0;

        $total_komisi = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $karyawan->id)->sum('komisi');
        $total_penjualan_all = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $karyawan->id)->sum('harga_jual');

        if ($total_penjualan_all >= $karyawan->target_pendapatan) {
            $bonus = $karyawan->bonus_target;
        }
        $tipeGajih = $karyawan->hasJabatan->gajih;
        $total_tip = 0;


        $total_tip = PenjualanDetail::whereBetween('created_at', [$start, $end])->where('karyawan_id', $karyawan->id)->sum('tip');
        $jumlahLembur = $lembur * $karyawan->lembur;
        if ($tipeGajih == 'perbulan') {
            $gajipokok = $karyawan->hasJabatan->gajih_perbulan;
            $totalGajih = $karyawan->hasJabatan->gajih_perbulan + $total_tip  + ($jumlahLembur);
        } else {
            $gajipokok = $karyawan->hasJabatan->gajih_perhari;
            $totalGajih = $karyawan->hasJabatan->gajih_perhari * $absensi  + ($jumlahLembur);
        }

        $kasbon = KasBon::where('karyawan_id', $karyawan->id)->whereStatus('belum')->get()->sum('uang');

        $thp = (($totalGajih + $total_komisi + $bonus) - $denda);

        return view('penggajian.show', compact(
            'no',
            'title',
            'penggajian',
            'slug',
            'karyawan',
            'denda',
            'requestdate',
            'absensi',
            'gajipokok',
            'jumlahLembur',
            'tipeGajih',
            'totalGajih',
            'lembur',
            'total_komisi',
            'penjualanDetail',
            'total_penjualan_all',
            'bonus',
            'total_tip',
            'kasbon',
            'thp'
        ));
    }
}
