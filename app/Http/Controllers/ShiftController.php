<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Traits\CrudTrait;

class ShiftController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'shift';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name' => 'name',
                'alias' => 'Shift'
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
                'alias' => 'Shift',
                'value' => null
            ],
        ];
    }

    public function configForm()
    {
        return [
            [
                'name' => 'name',
                'input' => 'text',
                'alias' => 'Shift',
                'validasi' => ['required', 'min:1'],
            ],
            [
                'name' => 'kode',
                'input' => 'text',
                'alias' => 'Kode Shift',
                'validasi' => ['required', 'min:1'],
            ],
            [
                'name' => 'jam_masuk',
                'input' => 'time',
                'alias' => 'Jam Masuk',
            ],
            [
                'name' => 'jam_pulang',
                'input' => 'time',
                'alias' => 'Jam Pulang',
            ],
        ];
    }

    public function model()
    {
        return new Shift();
    }
}
