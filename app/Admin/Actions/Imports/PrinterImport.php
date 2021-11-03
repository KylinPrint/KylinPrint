<?php

namespace App\Admin\Actions\Imports;


use App\Models\Bind;
use App\Models\Brand;
use App\Models\Printer;
use App\Models\Solution;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use function PHPUnit\Framework\isEmpty;

HeadingRowFormatter::default('none');
class PrinterImport implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
        $curPrinterModel = Printer::where('model','=', $row['model(英文型号)'])->pluck('id');

        //数据重复逻辑,需连同品牌
        if ($curPrinterModel) {
            foreach($curPrinterModel as $value)
            {
                $row['manufacter(厂商英文名)'] == Brand::where('id',$value)->pluck('name')->first();
                return null;
            }
        }

        $curBrandId = Brand::where('name_en',$row['manufacter(厂商英文名)'])->pluck('id')->first();
        
        if($row['是否彩打'] == '彩色') $curType = 'color';
        elseif($row['是否彩打'] == '黑白') $curType = 'mono';
        else $curType = null;

        if($row['分类2'] == '热敏打印机') $curPrinciple = '1';
        elseif($row['分类2'] == '喷墨打印机') $curPrinciple = '2';
        elseif($row['分类2'] == '激光打印机') $curPrinciple = '3';
        elseif($row['分类2'] == '针式打印机') $curPrinciple = '4';
        else $curPrinciple = '5';

        if ($row['上市日期'] == '暂无数据' || !isset($row['上市日期'])){$date = null;}
        else {$date = date('Y-m-d', ($row ['上市日期'] - 25569) * 24 * 3600);}

        if($row['是否停产'] == '停产'){$curOnsale = 0;}
        elseif($row['是否停产'] == '在售'){$curOnsale = 1;}
        else $curOnsale = 2;

        if($row['网络打印'] == 0 || !isset($row['网络打印'])){$curNetwork = 2;}
        elseif ($row['网络打印'] = '支持') {$curNetwork = 1;}
        elseif ($row['网络打印'] = '不支持') {$curNetwork = 0;} 

        if($row['双面打印'] == '双面'){$curDuplex = 'duplex';}
        else $curDuplex = 'single';

        if($row['最大支持幅面'] == 0 || !isset($row['双面打印'])){$curPagesize = '暂无数据';}
        else $curPagesize = $row['最大支持幅面'];

        if($row['是否主流在售'] == '主流在售') {$curMainstream = 1;}
        else {$curMainstream = 0;}

        $curAdapterStatu = 0;
        if($row['适配状态'] == '通过')
        {
            $curAdapterStatu = 1;
            if(isEmpty($row['测试方式']))
            {
                $curAdapterStatu = 2;
            }
        }

        

        return new Printer([
                'brands_id' => $curBrandId,
                'model' => $row['model(英文型号)'],
                'type' => $curType,
                'principle_tags_id' => $curPrinciple, 
                'release_date' => $date,
                'onsale' => $curOnsale,
                'network' => $curNetwork,
                'duplex' => $curDuplex,
                'pagesize' => $curPagesize,
                'mainstream' => $curMainstream,
                'language' => $row['打印语言'],
                'adapter_status' => $curAdapterStatu,
            ]);

    }



    //批量导入1000条
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