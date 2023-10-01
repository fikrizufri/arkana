<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Traits\CrudTrait;

class KaryawanController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'karyawan';
        $this->sort = 'nama';
        $this->plural = 'true';
        $this->manyToMany = ['role'];
        $this->relations = ['user'];
        $this->extraFrom = ['user', 'jabatan'];
        $this->oneToMany = ['toko'];
        $this->hasValue = 'toko';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama',
                'alias'    => 'Nama Karyawan',
            ],
            [
                'name'    => 'nama_jabatan',
                'alias'    => 'Nama Jabatan',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Karyawan',
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
                'alias'    => 'Nama Karyawan',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'nik',
                'input'    => 'text',
                'alias'    => 'Nomor KTP',
                'validasi'    => ['required', 'min:1', 'unique'],
            ],
            [
                'name'    => 'bank',
                'input'    => 'text',
                'alias'    => 'Bank',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'no_rek',
                'input'    => 'text',
                'alias'    => 'Nomor Rekening',
                'validasi'    => ['required', 'min:1', 'unique'],
            ],
            [
                'name'    => 'jabatan_id',
                'input'    => 'combo',
                'alias'    => 'Jabatan',
                'value' => $this->combobox('Jabatan'),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'username',
                'alias'    => 'Username',
                'validasi'    => ['required', 'unique', 'min:3', 'plural'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'password',
                'alias'    => 'Password',
                'input'    => 'password',
                'validasi'    => ['required', 'min:8'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'email',
                'alias'    => 'Email',
                'input'    => 'email',
                'validasi'    => ['required',  'plural', 'unique', 'email'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'role_id',
                'input'    => 'combo',
                'alias'    => 'Hak Akses',
                'value' => $this->combobox('Role', 'slug', 'superadmin', '!='),
                'validasi'    => ['required'],
                'extraForm' => 'user',
                'hasMany'    => ['role'],
            ],
            [
                'name'    => 'toko_id',
                'input'    => 'combo',
                'alias'    => 'Toko',
                'value' => $this->combobox('Toko'),
            ],
        ];
    }

    public function model()
    {
        return new Karyawan();
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->model()->find($id);
        $message = "Data ada";

        $data  = [
            'id' => $model->id,
            'nama' => ucwords($model->nama),
        ];
        $result = [
            'data' => $data,
            'message' =>  $message,
        ];
        return response()->json($result);
    }
}
