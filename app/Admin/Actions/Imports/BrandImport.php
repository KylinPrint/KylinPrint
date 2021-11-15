<?php

namespace App\Admin\Actions\Imports;


use App\Models\Brand;
use App\Models\Manufactor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class BrandImport implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
        $curBrandModel = Brand::where('name_en', $row['manufacter(厂商英文名)'])->first();
        
        if ($curBrandModel) {
            return null;
        }

        $curManufactorID = Manufactor::where('name',$row['厂商名称'])->pluck('id')->first();

        return new Brand([
            'name' => $row['品牌'],
            'name_en' => $row['manufacter(厂商英文名)'],
            'manufactors_id' => $curManufactorID,        
        ]);

    }

    public function batchSize(): int
    {
        return 100;
    }
    //以1000条数据基准切割数据
    public function chunkSize(): int
    {
        return 100;
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