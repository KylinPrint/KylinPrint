<?php

namespace App\Admin\Actions\Imports;


use App\Models\Solution;
use App\Models\Printer;
use App\Models\Bind;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use function PHPUnit\Framework\isEmpty;

HeadingRowFormatter::default('none');

class SolutionMatchImport implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    use Importable;

    
    public function model(array $row)
    { 
        $curPrinterId = Printer::where('model',$row['型号'])->pluck('id')->first() ?? null;
        
        if($curPrinterId)
        {
            $curBindAdapter = Bind::where('id',$curPrinterId)->pluck('adapter','printers_id','solutions_id');
            $curAdapter = $row['系统版本'].'_'.$row['系统架构'];
            $curSolutionName = "";
            $curSolutionDetail = "";

            foreach($curBindAdapter as $AdapterArr)
            {
                foreach($AdapterArr as $value)
                {
                    if($curAdapter == $value['adapter'])
                    {
                        $curSolutionName = Solution::where('id',$value['solutions_id'])->pluck('name')->first();
                        $curSolutionDetail = Solution::where('id',$value['solutions_id'])->pluck('detail')->first();
                    }
                }
            }
        }

        return new Solution(
            [
            'name' => $row['型号'],
            'comment' => $row['厂商'],
            'source' => $curAdapter,
            'detail' => $curSolutionDetail ?? null,
            ]);

    }

    public function batchSize(): int
    {
        return 100;
    }
    //以100条数据基准切割数据
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