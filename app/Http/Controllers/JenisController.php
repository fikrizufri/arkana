<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Produk;
use App\Traits\CrudTrait;

class JenisController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'jenis';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama',
                'alias'    => 'Nama Jenis',
            ],
            [
                'name'    => 'nama_kategori',
                'alias'    => 'Nama Kategori',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Jenis',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Jenis',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'kategori_id',
                'input'    => 'combo',
                'alias'    => 'Kategori',
                'value' => $this->combobox('kategori'),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'inc_nota',
                'alias'    => 'Nota',
                'input'    => 'radio',
                'value' => ['ya', 'tidak'],
                'validasi'    => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new Jenis();
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Produk::where('jenis_id', $id)->where('inc_jual', 'ya')->get();
        $message = "List Produk Ada";

        $data  = [];

        foreach ($model as $key => $value) {
            $data[$key]  = [
                'id' => $value->id,
                'nama' => $value->nama . ' | Jenis : ' . $value->jenis,
            ];
        }
        if (!$data) {
            $message = "Produk tidak ada";
        }
        $result = [
            'data' => $data,
            'message' =>  $message,
        ];
        return response()->json($result);
    }
}
