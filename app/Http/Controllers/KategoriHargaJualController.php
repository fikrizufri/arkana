<?php

namespace App\Http\Controllers;

use App\Models\KategoriHargaJual;
use App\Traits\CrudTrait;

class KategoriHargaJualController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'kategori_harga_jual';
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
                'alias'    => 'Nama Kategori',
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
                'alias'    => 'Nama Kategori',
                'validasi'    => ['required', 'unique', 'min:1'],
            ]
        ];
    }

    public function model()
    {
        return new KategoriHargaJual();
    }
}
