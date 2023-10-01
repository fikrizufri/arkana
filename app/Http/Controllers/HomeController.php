<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Jenis;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!request()->user()->hasRole('superadmin')) {
            return redirect()->route('penggajian.detail');
        }
        Carbon::setLocale('id');
        $title =  "Dashboard";

        $anggota = 0;
        $pegawai = 0;
        $rapat = 0;
        $jenisRapat = 0;

        $now = Carbon::now()->subMonth(1);
        $now = bulan_indonesia($now);

        $listCustomer = Pelanggan::orderBy('jumlah_nota', 'desc')->limit(6)->get();
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $startBulanLalu = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d H:i:s');
        $endBulanLalu = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d H:i:s');

        $hariIni = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $akhirHariIni = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
        $tahun = Carbon::now()->formatLocalized("%Y");

        $penjualanToday =  Penjualan::whereBetween('created_at', [$start, $end]);
        $penjualan =  $penjualanToday->sum('grandtotal');
        $bankCash = Bank::whereSlug('cash')->first();
        $penjualanBulanLaluCash =  Penjualan::where('bank_id', $bankCash->id)->whereBetween('created_at', [$startBulanLalu, $endBulanLalu])->sum('grandtotal');
        $penjualanBulanLaluTrasnfer =  Penjualan::where('bank_id', "!=", $bankCash->id)->whereBetween('created_at', [$startBulanLalu, $endBulanLalu])->sum('grandtotal');

        $penjualanBulanCash =  Penjualan::where('bank_id', $bankCash->id)->whereBetween('created_at', [$hariIni, $akhirHariIni])->sum('grandtotal');
        $penjualanBulanTrasnfer =  Penjualan::where('bank_id', "!=", $bankCash->id)->whereBetween('created_at', [$hariIni, $akhirHariIni])->sum('grandtotal');

        $penjualanHariIni = Penjualan::whereBetween('created_at', [$hariIni, $akhirHariIni])->sum('grandtotal');
        $penjualanBulanLalu = Penjualan::whereBetween('created_at', [$startBulanLalu, $endBulanLalu])->sum('grandtotal');
        $customer =  count($penjualanToday->get()->groupBy('pelanggan_id'));

        $penjualanSetahun =  Penjualan::selectRaw('year(created_at) as tahun, month(created_at) as bulan, count(*) as jumlah, sum(grandtotal) as grandtotal')
            ->whereYear('created_at', $tahun)
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->get();

        $getPenjualanPerbulan = [];
        $penjualanPerbulan = [];
        $penjualanPerbulanGrafik = [];
        $month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($penjualanSetahun as $index => $booking) {

            $getPenjualanPerbulan[$booking->bulan] = [
                'bulan' => $booking->bulan,
                'jumlah' => $booking->jumlah,
                'grandtotal' => $booking->grandtotal
            ];
        }
        // return $getPenjualanPerbulan;
        foreach ($month as $key => $bulan) {

            if (!empty($getPenjualanPerbulan[$bulan])) {
                $penjualanPerbulan[$key] = $getPenjualanPerbulan[$bulan];
                $penjualanPerbulanGrafik[$key] = [
                    bulan_indonesia(Carbon::create()->month($bulan)->year($tahun)->format('Y-m-d')),
                    (int) $getPenjualanPerbulan[$bulan]['grandtotal']
                ];
            } else {
                $penjualanPerbulan[$key] = [
                    bulan_indonesia(Carbon::create()->month($bulan)->year($tahun)->format('Y-m-d')),
                    0
                ];
                $penjualanPerbulanGrafik[$key] = [
                    bulan_indonesia(Carbon::create()->month($bulan)->year($tahun)->format('Y-m-d')),
                    0
                ];
            }
        };
        $layanan =  $penjualanToday->with('hasProduk')->get();

        $listKaryawan = Karyawan::get();

        $PenjualanTerbaik = PenjualanDetail::selectRaw('count(*) as jumlah_layanan, sum(harga_jual) as total_penjualan, max(karyawan_id) as karyawan_id')->groupBy('karyawan_id')->orderBy('jumlah_layanan', 'desc')->first();

        $karyawanTerbaik = Karyawan::where('id', $PenjualanTerbaik->karyawan_id)->first();

        $layananTerbaik = Produk::withCount('hasPenjualan')->orderBy('has_penjualan_count', 'desc')->first();

        $jenisLayanan = Jenis::whereSlug('Layanan')->first();
        $jenisProduk = Jenis::whereSlug('minuman')->orWhere('slug', 'makanan')->get()->pluck('id');
        $listlayanan = Produk::where('jenis_id', $jenisLayanan->id)->get();
        $listlayanan = $listlayanan->sortByDesc(function ($product) {
            return $product->total;
        });

        $listProduk = Produk::whereIn('jenis_id', $jenisProduk)->get();
        $listProduk = $listProduk->sortByDesc(function ($product) {
            return $product->total;
        });

        $penjualan_sum_penjualan = 0;
        $namakaryawanTerbaik = '';
        if ($karyawanTerbaik) {
            $namakaryawanTerbaik = $karyawanTerbaik->nama;
            $penjualan_sum_penjualan = format_uang($PenjualanTerbaik->total_penjualan);
        }
        // return grafikperbulan
        return view('home.index', compact(
            'title',
            'customer',
            'tahun',
            'now',
            'listCustomer',
            'listKaryawan',
            'listlayanan',
            'listProduk',
            'penjualanSetahun',
            'penjualanBulanLalu',
            'penjualanHariIni',
            'penjualanBulanLaluCash',
            'penjualanBulanLaluTrasnfer',
            'layananTerbaik',
            'namakaryawanTerbaik',
            'penjualan_sum_penjualan',
            'karyawanTerbaik',
            'penjualanPerbulan',
            'penjualanPerbulanGrafik',
            'penjualan',
            'rapat',
            'jenisRapat'
        ));
    }
}
