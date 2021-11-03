<?php

namespace App\Admin\Actions\Imports;


use App\Models\Bind;
use App\Models\Printer;
use App\Models\Solution;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use function PHPUnit\Framework\isEmpty;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');
class BindImport implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        if(!isset($row['解决方案名'])){return null;}
        
        $curPrinterId = Printer::where('model',$row['model(英文型号)'])->pluck('id')->first();
        $curSolutionId = Solution::where('name',$row['解决方案名'])->pluck('id')->first();

        $curBindId = Bind::where('printers_id',$curPrinterId)->pluck('id'); //当前printer_id在bind中的id
        
        if(isset($curBindId))
        {
            foreach($curBindId as $value1)
            {
                $curBindSId = Bind::where('id',$value1)->pluck('solutions_id')->first();
                if($curBindSId == $curSolutionId)
                {
                    return null;
                }
            }
        }                     

        return new Bind([
                'printers_id' => $curPrinterId,   
                'solutions_id' => $curSolutionId,
                'adapter' => $row['适配']
        ]);

    }
    
    public function batchSize(): int
    {
        return 1000;
    }
    //以1000条数据基准切割数据
    public function chunkSize(): int
    {
        return 1000;
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