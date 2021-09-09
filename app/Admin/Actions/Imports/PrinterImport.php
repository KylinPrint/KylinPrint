<?php

namespace App\Admin\Actions\Imports;

use App\Models\Printer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PrinterImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
        $brands_id = Printer::where('brands_id', '=', $row[0])->first();
        if ($brands_id) {
            return null;
        }
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


    /**
     * 从第几行开始处理数据 就是不处理标题
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}