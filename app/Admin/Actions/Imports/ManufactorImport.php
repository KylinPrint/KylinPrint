<?php

namespace App\Admin\Actions\Imports;


use App\Models\Manufactor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ManufactorImport implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
        $curManufactorModel = Manufactor::where('name','=', $row['厂商名称'])->first();

        if ($curManufactorModel) {
            return null;
        }

        $curModel = new Manufactor([
            'name' => $row['厂商名称'],
            'isconnected' => $row['是否建联'],
        ]);

        return $curModel;

    }

    

    public function batchSize(): int
    {
        return 1;
    }
    //以1000条数据基准切割数据
    public function chunkSize(): int
    {
        return 1;
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