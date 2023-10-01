<?php

namespace App\Http\Controllers;

use App\Models\KasBon;
use App\Traits\CrudTrait;

class KasBonController extends Controller
{
    use CrudTrait;


    public function __construct()
    {
        $this->route = 'kas-bon';
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
                'name' => 'nota_kasbon',
                'alias' => 'No Nota Kas Bon',
            ],
            [
                'name' => 'nama_karyawan',
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

    public function configForm()
    {
        return [
            [
                'name' => 'uang',
                'input' => 'rupiah',
                'alias' => 'Uang yang dipinjam',
            ],
            [
                'name'  => 'karyawan_id',
                'input' => 'combo',
                'alias' => 'Karyawan',
                'value' => $this->combobox(
                    'Karyawan',
                ),
            ],
        ];
    }

    public function model()
    {
        return new KasBon;
    }

    public function detail($id)
    {
        $model = $this->model()->find($id);
        $message = "Kas Bon ada";
        $data  = [
            'id' => $model->id,
            'uang' => $model->uang,
        ];
        if (!$data) {
            $data = [];
            $message = "KasBonController tidak ada";
        }
        $result = [
            'data' => $data,
            'message' =>  $message,
        ];
        return response()->json($result);
    }
}
