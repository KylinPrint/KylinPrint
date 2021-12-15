<?php

namespace App\Admin\Actions\Imports;


use App\Models\Bind;
use App\Models\Printer;
use App\Models\Solution;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');
class BindUpdate implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithHeadingRow, WithUpserts//, WithUpsertColumns
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

        if(!isset($curSolutionId)){return null;}

        $curBindId = Bind::where('printers_id',$curPrinterId)->pluck('id'); //当前printer_id在bind中的id

        $curBindAdapter = $row['适配'];

        if($row['适配状态'] == '通过'){$curBindCheck = 3;}else{return null;}
        
        if($row['更新时间']){
            $curComplet = date("Y-m-d",($row['更新时间']-25569)*3600*24);
        }else{$curComplet = null;}

        return new Bind([
                'printers_id' => $curPrinterId,   
                'solutions_id' => $curSolutionId,
                'adapter' => $curBindAdapter,
                'checked' => $curBindCheck,
                'comments' => $row['备注'],
                'completion_time' => $curComplet,
                'auth' => $row['兼容等级'] = 'CERTIFICATION'?1:2,
        ]);

    }

    public function uniqueBy()
    {
        return 'unique_binds';
    }

    public function upsertColumns() 
    {
        return ['checked','auth',];
    }
    
    public function batchSize(): int
    {
        return 5;
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