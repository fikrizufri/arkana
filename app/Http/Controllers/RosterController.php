<?php

namespace App\Http\Controllers;

use App\Downloads\RosterTemplate;
use App\Models\Roster;
use Illuminate\Http\Request;
use App\Traits\CrudTrait;
use App\Imports\RosterImport;
use App\Models\Karyawan;
use App\Models\Shift;
use Excel;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RosterController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'roster';
        $this->import = 'roster.import';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name' => 'tanggal',
                'alias' => 'Tanggal',
                'input' => 'date',
            ],
            [
                'name' => 'name_shift',
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
        $month = [];

        for ($m = 1; $m <= 12; $m++) {
            $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }
        return [
            [
                'name' => 'nama',
                'input' => 'text',
                'alias' => 'Nama',
                'value' => null
            ],
            [
                'name'    => 'bulan',
                'input'    => 'combo',
                'alias'    => 'Bulan',
                'value' => $month,
                'validasi'    => ['required'],
            ],
        ];
    }

    public function configForm()
    {
        return [
            [
                'name'  => 'tanggal',
                'input' => 'date',
                'alias' => 'Tanggal',
                'validasi'  => ['required', 'date'],
            ],
            [
                'name'    => 'shift_id',
                'input'    => 'combo',
                'alias'    => 'Shift',
                'value' => $this->combobox('shift', null, null, null, 'name'),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'karyawan_id',
                'input'    => 'combo',
                'alias'    => 'Karyawan',
                'value' => $this->combobox('Karyawan', null, null, null, 'Nama'),
                'validasi'    => ['required'],
            ],
            [
                'name' => 'jam_masuk',
                'input' => 'time',
                'alias' => 'Jam Masuk',
                'validasi' => ['required'],
            ],
            [
                'name' => 'jam_pulang',
                'input' => 'time',
                'alias' => 'Jam Pulang',
                'validasi' => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new Roster;
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
        $query = Karyawan::query();

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
        $tahun = Carbon::now()->formatLocalized("%Y");
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        $searchBulan = '';
        $searchBulanDipilih = '';
        foreach ($searches as $key => $val) {
            $search[$key] = request()->input($val['name']);
            $hasilSearch[$val['name']] = $search[$key];

            if ($val['name'] == 'bulan') {
                if ($search[$key]) {
                    $searchBulan = '1 ' . $search[$key];
                    $start = Carbon::parse($searchBulan)->startOfMonth()->format('Y-m-d H:i:s');
                    $end = Carbon::parse($searchBulan)->endOfMonth()->format('Y-m-d H:i:s');
                    $searchBulanDipilih = Carbon::parse($searchBulan)->month;
                }
                continue;
            }
            if ($search[$key]) {
                $query = $query->where($val['name'], 'like', '%' . $search[$key] . '%');
            }
            $export .= $val['name'] . '=' . $search[$key] . '&';
        }

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

        $month = [];

        for ($m = 1; $m <= 12; $m++) {
            $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }
        $tanggal = Carbon::now()->daysInMonth;
        if ($searchBulan) {
            $tanggal = Carbon::parse($searchBulan)->daysInMonth;
        }
        $bulan = Carbon::now()->month;
        $hari = Carbon::now();
        $hariini = Carbon::now()->day;
        $listKaryawn = [];

        $tanggalHariIni = [];
        for ($i = 0; $i < $tanggal; $i++) {
            $tanggalHariIni[$i] = $i + 1;
        }
        // return $tanggalHariIni;
        foreach ($data as $key => $value) {
            $listKaryawn[$key]  = [
                'karyawan_id' => $value->id,
                'nama' => $value->nama,
                'nik' => $value->nik
            ];
            foreach ($value->hasRoster()->getQuery()->whereBetween('rosters.tanggal', [$start, $end])->get() as $i => $item) {
                $index =  Carbon::parse($item->tanggal)->day;
                $listKaryawn[$key]['shift'][$index] = [
                    'roster_id' => $item->id,
                    'tanggal' =>  Carbon::parse($item->tanggal),
                    'shift_id' => $item->shift_id,
                    'shift' => $item->name_shift
                ];
            }
        }
        // return $listKaryawn;
        $listKaryawnShift = [];
        foreach ($listKaryawn as $key => $value) {
            $listKaryawnShift[$key] =  [
                'nama' => $value['nama'],
                'nik' => $value['nik']
            ];
            for ($i = 0; $i < $tanggal; $i++) {
                if (!empty($listKaryawn[$key]['shift'][$i + 1])) {
                    $listKaryawnShift[$key]['shift'][$i + 1] = [
                        'karyawan_id' => $value['karyawan_id'],
                        'roster_id' => $listKaryawn[$key]['shift'][$i + 1]['roster_id'],
                        'no' => $i + 1,
                        'tanggal' => $listKaryawn[$key]['shift'][$i + 1]['tanggal'],
                        'shift_id' => $listKaryawn[$key]['shift'][$i + 1]['shift_id'],
                        'shift' => $listKaryawn[$key]['shift'][$i + 1]['shift']
                    ];
                } else {
                    $tgl =  $i + 1;
                    $listKaryawnShift[$key]['shift'][$i + 1] = [
                        'karyawan_id' => $value['karyawan_id'],
                        'roster_id' => '',
                        'no' => $i + 1,
                        'tanggal' =>   $tahun . '-' . $bulan . "-"  . $tgl,
                        'shift_id' => '',
                        'shift' => ''
                    ];
                }
            }
        }

        $listKaryawnShift =  collect($listKaryawnShift);
        $shift = Shift::orderBy('name')->get();

        return view('roster.index',  compact(
            "title",
            "data",
            'searches',
            'tanggal',
            'shift',
            'hariini',
            'hasilSearch',
            'import',
            'button',
            'month',
            'bulan',
            'searchBulanDipilih',
            'search',
            'export',
            'listKaryawnShift',
            'configHeaders',
            'route'
        ));
    }

    public function import()
    {
        $title = "Roster Import";
        $action = "roster.import_process";
        $month = [];

        for ($m = 1; $m <= 12; $m++) {
            $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }

        return view('roster.import', compact('title', 'action', 'month'));
    }

    public function import_process(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        DB::beginTransaction();
        $tahun = Carbon::now()->formatLocalized("%Y");
        $tanggal = Carbon::now()->daysInMonth;
        $bulan = $request->bulan;
        $listKaryawan = [];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $rosters = Excel::toArray(new RosterImport, $file);

            foreach ($rosters[0] as $key => $value) {
                for ($i = 0; $i < $tanggal; $i++) {
                    $tgl =  $i + 1;
                    $listKaryawan[$key][$i] =  (object) [
                        'nama' => $value[1],
                        'nik' => $value[2],
                        'shift' => $value[$i + 3],
                        'tanggal' =>  Carbon::parse($tahun . '-' . $bulan . "-"  . $tgl)->format('Y-m-d'),
                    ];
                }
            }
            // return $listKaryawan;
            $nama_shift = [];
            $nama = [];
            $nik = [];
            $tanggalKaryawan = [];
            $shift = [];
            $karyawan = [];
            $roster = [];
            $data = [];
            $cek = [];
            foreach ($listKaryawan as $key => $value) {

                for ($i = 0; $i < $tanggal; $i++) {
                    $nama[$key][$i] = $value[$i]->nama;
                    $nik[$key][$i] = $value[$i]->nik;
                    $karyawan[$key][$i] = Karyawan::whereNik($nik[$key][$i])->orWhere('nama', $nama[$key][$i])->first();
                    if (isset($karyawan[$key][$i])) {
                        $karyawan_id[$key][$i] = $karyawan[$key][$i]->id;
                        $nama_shift[$key][$i] = $value[$i]->shift;
                        $shift[$key][$i] = Shift::whereName($nama_shift[$key][$i])->first();
                        $tanggalKaryawan[$key][$i] = $value[$i]->tanggal;

                        if ($shift[$key][$i]) {
                            $roster[$key][$i] = Roster::where('karyawan_id', $karyawan[$key][$i]->id)->where('tanggal', $tanggalKaryawan[$key][$i])->first();

                            if (!$roster[$key][$i]) {
                                $data[$key][$i] =  new Roster();
                                $cek[$key][$i] = ['baru', $nama_shift[$key][$i], $tanggalKaryawan[$key][$i],  $shift[$key][$i]->id];
                                $data[$key][$i]->karyawan_id =  $karyawan_id[$key][$i];
                                $data[$key][$i]->shift_id =  $shift[$key][$i]->id;
                                $data[$key][$i]->jam_masuk =  $shift[$key][$i]->jam_masuk;
                                $data[$key][$i]->jam_pulang =  $shift[$key][$i]->jam_pulang;
                                $data[$key][$i]->tanggal =  $tanggalKaryawan[$key][$i];
                                $data[$key][$i]->save();
                            } else {
                                $data[$key][$i] = Roster::find($roster[$key][$i]->id);
                                $cek[$key][$i] = ['berubah', $nama_shift[$key][$i], $tanggalKaryawan[$key][$i],  $shift[$key][$i]->id];
                                $data[$key][$i]->karyawan_id =  $karyawan_id[$key][$i];
                                $data[$key][$i]->shift_id =  $shift[$key][$i]->id;
                                $data[$key][$i]->jam_masuk =  $shift[$key][$i]->jam_masuk;
                                $data[$key][$i]->jam_pulang =  $shift[$key][$i]->jam_pulang;
                                $data[$key][$i]->tanggal =  $tanggalKaryawan[$key][$i];
                                $data[$key][$i]->save();
                            }
                        }
                    }
                }
            }

            // return $cek;

            try {
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Import Roster')->with('Class', 'dangger');
            }

            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Import Roster')->with('Class', 'success');
        }

        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Gagal Import Roster')->with('Class', 'dangger');
    }

    public function process(Request $request)
    {
        $shift_id = $request->shift_id;
        $roster_id = $request->roster_id;
        $karyawan_id = $request->karyawan_id;
        $tanggal = $request->tanggal;

        $shift = Shift::find($shift_id);
        if ($roster_id) {
            $data = $this->model()->find($roster_id);
        } else {
            $data =  $this->model();
        }
        // return response()->json($roster_id);
        $data->karyawan_id =  $karyawan_id;
        $data->tanggal =  $tanggal;
        $data->shift_id =  $shift_id;
        if ($shift) {
            $data->jam_masuk =  $shift->jam_masuk;
            $data->jam_pulang =  $shift->jam_pulang;
        }
        $data->save();
        return response()->json($data);
        Session::flash('message', 'Berhasil update roster');
    }

    public function download()
    {
        $bulan = Carbon::now()->formatLocalized("%m");
        return Excel::download(new RosterTemplate($bulan), 'Download Template Roster Bulan ' . $bulan . '.xlsx');
    }
}
