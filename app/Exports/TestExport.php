<?php

namespace App\Exports;

use App\Models\Printer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;

class TestExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Query
    */
    public function query()
    {
        return Printer::query()->where('model','SK-820');
    }
    
}
