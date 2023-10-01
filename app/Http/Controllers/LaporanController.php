<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->route = 'laporan';
        $this->paginate = 50;
        $this->import = null;
        $this->user = null;
        $this->middleware('role:admin');
    }


    public function configHeadersPenjualan()
    {
        return [
            [
                'name'    => 'no_nota',
                'alias'    => 'No Nota',
            ],
            [
                'name'    => 'pelanggan',
                'alias'    => 'Nama Pelanggan',
            ],
            [
                'name'    => 'tanggal_tampil',
                'alias'    => 'Tanggal',
            ],
            [
                'name'    => 'grandtotal',
                'input'    => 'rupiah',
                'alias'    => 'Total Penjualan',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'no_nota',
                'input'    => 'text',
                'alias'    => 'No Nota',
                'value'    => null
            ],
            [
                'name'    => 'tanggal',
                'input'    => 'daterange',
                'alias'    => 'Tanggal',
                'value'    => null
            ],
        ];
    }

    public function configHeadersPembelian()
    {
        return [
            [
                'name'    => 'no_nota',
                'alias'    => 'No Nota',
            ],
            [
                'name'    => 'nama_supplier',
                'alias'    => 'Nama Supplier',
            ],
            [
                'name'    => 'tanggal_tampil',
                'alias'    => 'Tanggal',
            ],
            [
                'name'    => 'total_beli',
                'input'    => 'rupiah',
                'alias'    => 'Total Pembelian',
            ],
        ];
    }
    public function configSearchPembelian()
    {
        return [
            [
                'name'    => 'no_nota',
                'input'    => 'text',
                'alias'    => 'No Nota',
                'value'    => null
            ],
            [
                'name'    => 'tanggal',
                'input'    => 'daterange',
                'alias'    => 'Tanggal',
                'value'    => null
            ],
        ];
    }

    public function Penjualan()
    {
        $title = ucwords($this->route) . " Penjualan";

        //nama route
        $route =  $this->route;

        //nama relation

        //nama jumlah pagination
        $paginate =  $this->paginate;

        //declare nilai serch pertama
        $search = null;

        //memanggil configHeadersPenjualan
        $configHeaders = $this->configHeadersPenjualan();

        //memangil model peratama
        $query = Penjualan::query();

        //button
        $button = null;

        //import
        $import = null;

        if ($this->import) {
            $import = $this->import;
        }

        $export = null;

        //mulai pencarian --------------------------------
        $searches = $this->configSearch();
        $start =  Carbon::now()->startOfMonth()->format('Y-m-d') . ' 00:00:01';;
        $end =  Carbon::now()->endOfMonth()->format('Y-m-d') . ' 23:59:59';
        foreach ($searches as $key => $val) {
            $search[$key] = request()->input($val['name']);
            $hasilSearch[$val['name']] = $search[$key];
            if ($val['name'] == 'tanggal') {
                if (!empty(request()->input($val['name']))) {
                    $date = explode(' - ', request()->input($val['name']));
                    # code...
                    $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
                    $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
                }

                continue;
            }
            if ($search[$key]) {
                $query = $query->where($val['name'], 'like', '%' . $search[$key] . '%');
            }
            $export .= $val['name'] . '=' . $search[$key] . '&';
        }

        $query = $query->whereBetween('created_at', [$start, $end]);
        if (request()->input('from') && request()->input('to')) {
            $from =   request()->input('from') ?: '';
            $to = request()->input('to') ?: '';
            $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($from, $to));
            $export .= 'from=' . $from . '&to=' . $to;
        }
        //akhir pencarian --------------------------------
        // relatio
        // sort by
        if ($this->user) {
            if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('admin')) {
                $query->where('user_id', Auth::user()->id);
            }
        }
        $data = $query->orderBy('created_at', 'desc');
        //mendapilkan data model setelah query pencarian
        $totalRecord = $query->get()->sum('grandtotal');
        if ($paginate) {
            $data = $query->paginate($paginate);
        } else {
            $data = $query->get();
        }


        return view('laporan.penjualan.index',  compact(
            "title",
            "data",
            'searches',
            'hasilSearch',
            'totalRecord',
            'import',
            'button',
            'search',
            'export',
            'configHeaders',
            'route'
        ));
    }

    public function detailPenjualan($slug)
    {
        $query = Penjualan::query()->with('hasProduk')->whereSlug($slug);
        $data =  $query->first();
        $title = ucwords($this->route) . " Penjualan | No Nota : " . $data->no_nota;
        $penjualanDetail = PenjualanDetail::where('penjualan_id', $data->id)->get();


        return view('laporan.penjualan.show',  compact(
            "title",
            "data",
            "penjualanDetail",
        ));
    }

    public function Pembelian()
    {
        $title = ucwords($this->route) . " Pembelian";

        //nama route
        $route =  $this->route;

        //nama relation

        //nama jumlah pagination
        $paginate =  $this->paginate;

        //declare nilai serch pertama
        $search = null;

        //memanggil configHeadersPenjualan
        $configHeaders = $this->configHeadersPembelian();

        //memangil model peratama
        $query = Pembelian::query();

        //button
        $button = null;

        //import
        $import = null;

        if ($this->import) {
            $import = $this->import;
        }

        $export = null;

        //mulai pencarian --------------------------------
        $searches = $this->configSearch();
        $start =  Carbon::now()->startOfMonth()->format('Y-m-d') . ' 00:00:01';
        $end =  Carbon::now()->endOfMonth()->format('Y-m-d') . ' 23:59:59';
        foreach ($searches as $key => $val) {
            $search[$key] = request()->input($val['name']);
            $hasilSearch[$val['name']] = $search[$key];
            if ($val['name'] == 'tanggal') {
                if (!empty(request()->input($val['name']))) {
                    $date = explode(' - ', request()->input($val['name']));
                    # code...
                    $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
                    $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
                }

                continue;
            }
            if ($search[$key]) {
                $query = $query->where($val['name'], 'like', '%' . $search[$key] . '%');
            }
            $export .= $val['name'] . '=' . $search[$key] . '&';
        }

        $query = $query->whereBetween('created_at', [$start, $end]);
        if (request()->input('from') && request()->input('to')) {
            $from =   request()->input('from') ?: '';
            $to = request()->input('to') ?: '';
            $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($from, $to));
            $export .= 'from=' . $from . '&to=' . $to;
        }
        //akhir pencarian --------------------------------
        // relatio
        // sort by
        if ($this->user) {
            if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('admin')) {
                $query->where('user_id', Auth::user()->id);
            }
        }
        $query = $query->with('hasProduk')->orderBy('created_at', 'desc');
        //mendapilkan data model setelah query pencarian

        if ($paginate) {
            $data = $query->paginate($paginate);
        } else {
            $data = $query->get();
        }

        $totalRecord = $query->get()->sum('total_beli');


        return view('laporan.pembelian.index',  compact(
            "title",
            "data",
            'searches',
            'hasilSearch',
            'totalRecord',
            'import',
            'button',
            'search',
            'export',
            'configHeaders',
            'route'
        ));
    }

    public function detailPembelian($slug)
    {
        $query = Pembelian::query()->with('hasProduk')->whereSlug($slug)->first();
        $data =  $query->first();
        $title = ucwords($this->route) . " Pembelian | No Nota : " . $data->no_nota;
        $penjualanDetail =  $query->hasProduk;

        return view('laporan.pembelian.show',  compact(
            "title",
            "data",
            "penjualanDetail",
        ));
    }
}
