<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Auth;

class PembelianController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'pembelian';
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

        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'no_nota',
                'input'    => 'text',
                'alias'    => 'No Nota',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name' => 'supplier_id',
                'input' => 'combo',
                'alias' => 'Nama Supplier',
                'value' => $this->combobox(
                    'Supplier',
                ),
            ],

        ];
    }

    public function model()
    {
        return new Pembelian();
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

        $form = $this->configform();

        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);

        $JenisBahanBaku = Jenis::where('slug', 'layanan')->first();
        $dataProduk = Produk::where('jenis_id', '!=', $JenisBahanBaku->id)->get();
        return view('pembelian.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
            'countColomFooter',
            'store',
            'dataProduk',
            'route'
        ));
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

        $no_nota = $request->no_nota;
        $supplier_id = $request->supplier_id;
        $produk = $request->produk;

        $data = $this->model();
        $data->no_nota = $no_nota;
        $data->supplier_id = $supplier_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        if (isset($request->iditem)) {
            $qty = $request->qty;
            $item = [];
            $listitem = [];
            foreach ($qty as $index => $value) {
                $item[$index] = $request->iditem[$index];
                $produk[$index] = Produk::find($item[$index]);
                if ($produk[$index]) {
                    $produk[$index]->stok =  $produk[$index]->stok + $value;
                    $produk[$index]->harga_beli =  $request->harga_beli[$index];
                    $produk[$index]->save();
                }
                $listitem[$index] = [
                    'harga_beli' => $request->harga_beli[$index],
                    'qty' => $value
                ];
            }
            $syncData  = array_combine($item, $listitem);

            $data->hasProduk()->sync($syncData);
        }

        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Ditambahkan')->with('Class', 'success');
    }

    public function edit($id)
    {
        $data = $this->model()->with('hasProduk')->find($id);
        if (!isset($this->title)) {
            $title =  "Ubah " . ucwords(str_replace('-', ' ', $this->route)) . ' - : ' . $data->nama;
        } else {
            $title =  "Ubah " . ucwords(str_replace('-', ' ', $this->title)) . ' - : ' . $data->nama;
        }

        //nama route dan action route
        $route =  $this->route;
        $store =  "update";

        $form = $this->configform();
        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);

        $JenisBahanBaku = Jenis::where('slug', 'layanan')->first();
        $dataProduk = Produk::where('jenis_id', '!=', $JenisBahanBaku->id)->get();
        // return $data;

        return view('pembelian.form', compact(
            'route',
            'store',
            'colomField',
            'countColom',
            'countColomFooter',
            'dataProduk',
            'title',
            'form',
            'data'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        $getRequest = $this->getRequest($request, $id);
        $messages = $getRequest['messages'];
        $validation = $getRequest['validasi'];
        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );
        $no_nota = $request->no_nota;
        $supplier_id = $request->supplier_id;
        $produk = $request->produk;
        $data = $this->model()->find($id);
        $data->no_nota = $no_nota;
        $data->supplier_id = $supplier_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        if (isset($request->iditem)) {
            $qty = $request->qty;
            $item = [];
            $listitem = [];
            foreach ($qty as $index => $value) {
                $item[$index] = $request->iditem[$index];
                $produk[$index] = Produk::find($item[$index]);
                if ($produk[$index]) {
                    $produk[$index]->stok =  $produk[$index]->stok + $value;
                    $produk[$index]->harga_beli =  $request->harga_beli[$index];
                    $produk[$index]->save();
                }
                $listitem[$index] = [
                    'harga_beli' => $request->harga_beli[$index],
                    'qty' => $value
                ];
            }
            $syncData  = array_combine($item, $listitem);

            $data->hasProduk()->sync($syncData);
        }

        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Diuabh')->with('Class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dinamis
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->model()->find($id);
        $data->delete();

        $data->hasProduk()->detach();
        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil dihapus')->with('Class', 'danger');
    }
}
