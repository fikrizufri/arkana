<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Traits\CrudTrait;
use App\Imports\JadwalImport;
use App\Models\Karyawan;
use App\Models\Shift;
use Excel;
use Session;
use Carbon\Carbon;

class JadwalController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'jadwal';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name' => 'karyawan',
                'alias' => 'Nama Karyawan',
            ],
            [
                'name' => 'tanggal',
                'alias' => 'Tanggal',
                'input' => 'date',
            ],
            [
                'name' => 'shift',
                'alias' => 'Shift',
            ],
            [
                'name' => 'jam_masuk',
                'alias' => 'Jam Masuk',
            ],
            [
                'name' => 'jam_pulang',
                'alias' => 'Jam Pulang',
            ],
        ];
    }

    public function configSearch()
    {
        return [
            [
                'name' => 'name',
                'input' => 'text',
                'alias' => 'Nama',
                'value' => null
            ],
        ];
    }

    public function configForm()
    {
        return [
            [
                'name' => 'karyawan_id',
                'input' => 'combo',
                'alias' => 'Nama Karyawan',
                'value' => $this->combobox('Karyawan'),
            ],
            [
                'name'  => 'roster_id',
                'input' => 'combo',
                'alias' => 'Roster',
                'value' => $this->combobox(
                    'Roster',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    ['jadwal', 'shift', 'jam_masuk', 'jam_pulang'],
                ),
            ],
        ];
    }

    public function model()
    {
        return new Jadwal;
    }

    public function index()
    {
        //nama title
        if (!isset($this->title)) {
            $title =  ucwords($this->route);
        } else {
            $title =  ucwords($this->title);
        }

        //nama route
        $route =  $this->route;

        //nama relation
        $relations =  $this->relations;

        //nama jumlah pagination
        $paginate =  $this->paginate;

        //declare nilai serch pertama
        $search = null;

        //memanggil configHeaders
        $configHeaders = $this->configHeaders();

        //memangil model peratama
        $query = $this->model()::query();

        //button
        $button = null;

        //import
        $import = null;

        if ($this->import) {
            $import = $this->import;
        }

        $export = null;

        if ($this->configButton()) {
            $button = $this->configButton();
        }
        //mulai pencarian --------------------------------
        $searches = $this->configSearch();

        foreach ($searches as $key => $val) {
            $search[$key] = request()->input($val['name']);
            $hasilSearch[$val['name']] = $search[$key];
            if ($search[$key]) {
                $query = $query->where($val['name'], 'like', '%' . $search[$key] . '%');
            }
            $export .= $val['name'] . '=' . $search[$key] . '&';
        }

        // return
        if (request()->input('from') && request()->input('to')) {
            $from =   request()->input('from') ?: '';
            $to = request()->input('to') ?: '';

            // return $from;
            // $query = $query->whereDate('tgl', '<=', $from);
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
        if ($this->sort) {
            $data = $query->orderBy($this->sort);
        }
        //mendapilkan data model setelah query pencarian
        if ($paginate) {
            $data = $query->paginate($paginate);
        } else {
            $data = $query->get();
        }

        $karyawan = Karyawan::with('hasJadwal')->get();

        return $listKaryawan = Karyawan::with('hasJadwal')->get();

        $template = 'jadwal.index';
        // return  $export;

        $tanggal = Carbon::now()->daysInMonth;
        $bulan = Carbon::now()->month;

        $shift = Shift::get();

        // $rosterBulanIni =  Roster::selectRaw('year(created_at) as tahun, month(created_at) as bulan, count(*) as jumlah, sum(grandtotal) as grandtotal')
        //     ->whereMonth('created_at', $ $bulan)
        //     ->groupBy('tahun', 'bulan')
        //     ->orderBy('tahun', 'desc')
        //     ->get();

        return view('jadwal.index',  compact(
            "title",
            "data",
            'searches',
            'tanggal',
            'shift',
            'hasilSearch',
            'import',
            'button',
            'search',
            'export',
            'karyawan',
            'configHeaders',
            'route'
        ));
    }
}
