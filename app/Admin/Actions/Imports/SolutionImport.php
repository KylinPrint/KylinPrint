<?php

namespace App\Admin\Actions\Imports;


use App\Models\Solution;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use function PHPUnit\Framework\isEmpty;

HeadingRowFormatter::default('none');

class SolutionImport implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
        $curSolutionModel = Solution::where('name','=', $row['解决方案名'])->first(); 

        //$curDetail = '<p><a href="'.$row['url'].'" target="_blank" rel="noopener">'.$row['url'].'</a></p>';

        if ($curSolutionModel || !isset($row['解决方案名']))
        {
            return null;
        }

        $curDetail = '<p>'.$row['解决方案'].'</p>';
                
        return new Solution([
            'name' => $row['解决方案名'],
            'comment' => $row['备注'],
            'source' => 1,
            'detail' => $curDetail,
            ]);

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