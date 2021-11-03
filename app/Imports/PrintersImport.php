<?php

namespace App\Imports;

use App\Models\Printer;
use Maatwebsite\Excel\Concerns\ToModel;

class PrintersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Printer([
            'brands_id' => $row[0],
            'model' => $row[1],
            'type' => $row[2],
            'principle_tags_id' => $row[3],
            'release_date' => $row[4],
            'onsale' => $row[5],
            'network' => $row[6],
            'duplex' => $row[7],
            'pagesize' => $row[8],
            'adapter_status' => $row[9],
            'created_at' => $row[10],
            'updated_at' => $row[11],
        ]);
    }
}
