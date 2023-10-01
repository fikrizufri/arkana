<?php

namespace App\Http\Controllers;

use App\Models\KasBon;
use App\Models\PembayaranKasBon;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Auth;

class PembayaranKasBonController extends Controller
{
    use CrudTrait;


    public function __construct()
    {
        $this->route = 'pembayaran-kas-bon';
        $this->user = 'user';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name' => 'nota_pembayaran_kasbon',
                'alias' => 'No Nota Pembayaran Kas Bon',
            ],
            [
                'name' => 'no_nota_bon',
                'alias' => 'No Nota Kas Bon',
            ],
            [
                'name' => 'karyawan',
                'alias' => 'Nama Karyawan',
            ],
            [
                'name' => 'uang',
                'alias' => 'Uang',
                'input' => 'rupiah',
            ],
            [
                'name' => 'tanggal_tampil',
                'alias' => 'Tanggal',
            ],
        ];
    }

    public function configSearch()
    {
        return [
            [
                'name' => 'karyawan_id',
                'input' => 'combo',
                'alias' => 'Nama Karyawan',
                'value' => $this->combobox(
                    'Karyawan',
                ),
            ],
        ];
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
            if ($val['name'] == 'karyawan_id') {
                $karyawan_id = $search[$key];
                if ($search[$key] != '') {

                    $query = $query->whereHas('hasKasBon', function ($kasbon) use ($karyawan_id) {
                        $kasbon->where('karyawan_id', $karyawan_id);
                    });
                }
                continue;
            }
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

        // return  $export;

        return view('pembayaran.kasbon.index',  compact(
            "title",
            "data",
            'searches',
            'hasilSearch',
            'import',
            'button',
            'search',
            'export',
            'configHeaders',
            'route'
        ));
    }

    public function configForm()
    {
        return [
            [
                'name'  => 'kas_bon_id',
                'input' => 'combo',
                'alias' => 'Nota Kas Bon',
                'value' => $this->combobox(
                    'KasBon',
                    'status',
                    'belum',
                    '=',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    ['nota_kasbon']
                ),
            ],
            [
                'name' => 'uang',
                'input' => 'rupiah',
                'alias' => 'Uang yang dibayar',
            ],
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //nama title
        if (!isset($this->title)) {
            $title =  "Tambah " . ucwords($this->route);
        } else {
            $title =  "Tambah " . ucwords($this->title);
        }

        //nama route dan action route
        $route =  $this->route;
        $store =  "store";

        //memanggil config form
        $form = $this->configform();

        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);
        // $hasValue = $this->hasValue;

        return view('pembayaran.kasbon.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
            'countColomFooter',
            'store',
            'route'
            // 'hasValue'
        ));
    }

    public function model()
    {
        return new PembayaranKasBon;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //get dari post form
        $getRequest = $this->getRequest($request);
        // return $request;
        $validation = $getRequest['validasi'];
        $messages = $getRequest['messages'];
        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );

        $kasbon = KasBon::find($request->kas_bon_id);
        $uang = str_replace(".", "", $request->uang);
        if ($uang > $kasbon->uang) {
            return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Gagal Pembayaran Kas Bon, Pembayaran Kas Bon Harus pas atau kurang')->with('Class', 'danger');
        }
        $data = $this->model();
        $data->uang = $uang;
        $data->kas_bon_id = $request->kas_bon_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        if ($kasbon->uang == $uang) {
            $kasbon->status = "lunas";
        } else {
            $kasbon->uang = $kasbon->uang - $uang;
        }
        $kasbon->save();

        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Ditambahkan')->with('Class', 'success');
    }
}
