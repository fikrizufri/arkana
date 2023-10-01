<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Roster;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class RosterImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 5;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        return $collection;
    }
}
