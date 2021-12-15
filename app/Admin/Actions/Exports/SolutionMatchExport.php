<?php

namespace App\Admin\Actions\Exports;

use Dcat\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Solution;
use App\Models\Printer;
use App\Models\Bind;
use App\Models\Brand;

use function PHPUnit\Framework\isEmpty;

class SolutionMatchExport implements FromCollection, WithHeadings
{

    use Exportable;


    public function __construct($array,$file)
    {
        $time_start = $this->getmicrotime();
        $curMatchArr = $this->WWW($array);
        $time_end = $this->getmicrotime();
        $time = $time_end - $time_start;

        $this->data = $curMatchArr;

        $this->headings = [
            '厂商' ,
            '型号' ,
            '系统版本' ,
            '系统架构' ,
            '匹配型号结果' ,
        ];
        $this->file = $file;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings():array
    {
        return $this->headings;
    }

    // public function export()
    // {
    //     $this->download($this->file)->prepare(request())->send();
    //     exit;
    // }

    public function getmicrotime()
    {
        list($usec,$sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
    }


    public function WWW($array)
    {
        $i = 0;
        foreach($array as $curInput)
        {
            $curMatchArr[$i] = 
            [
                '厂商' => $curInput['厂商'],
                '型号' => $curInput['型号'],
                '系统版本' => $curInput['系统版本'],
                '系统架构' => $curInput['系统架构'],
                '匹配型号结果' => '暂无该型号数据',
            ];

            if(empty($curInput['厂商'])){
                $curMatchArr[$i]['匹配型号结果'] = '请核实产品品牌';
                ++$i;
                continue;
            }

            $curBrandId = (Brand::where('name',$curInput['厂商'])->pluck('id')->first())?:(Brand::where('name_en',$curInput['厂商'])->pluck('id')->first());
            
            if(empty($curBrandId)){
                $curMatchArr[$i]['解决方案名'] = '请核实产品品牌';
                ++$i;
                continue;
            }

            preg_match('/\d+/',$curInput['型号'],$InputNum);

            $curPrinterModelArr = Printer::where([
                ['model','like','%'.$InputNum[0].'%'],
                ['brands_id',$curBrandId],
            ])->pluck('model');
            
            if($curPrinterModelArr->isEmpty()){++$i;continue;}

            
            $curMatchArr[$i]['匹配型号结果'] = implode('/',$curPrinterModelArr->toArray());
            

            ++$i;
        }

        return $curMatchArr;
    }


    public function WTF($array){
        $i = 0;
        foreach($array as $curInput)
        {   
            $curMatchArr[$i] = 
            [
                '厂商' => $curInput['厂商'],
                '型号' => $curInput['型号'],
                '系统版本' => $curInput['系统版本'],
                '系统架构' => $curInput['系统架构'],
                '解决方案名' => '暂无适配方案',
                '解决方案详情' => null,
                '适配状态' => '未适配',
            ];

            if(empty($curInput['厂商'])){
                $curMatchArr[$i]['解决方案名'] = '请核实产品品牌';
                ++$i;
                continue;
            }

            $curBrandId = (Brand::where('name',$curInput['厂商'])->pluck('id')->first())?:(Brand::where('name_en',$curInput['厂商'])->pluck('id')->first());

            if(empty($curBrandId)){
                $curMatchArr[$i]['解决方案名'] = '请核实产品品牌';
                ++$i;
                continue;
            }

            preg_match('/\d+/',$curInput['型号'],$InputNum);

            $result = substr($curInput['型号'],strripos($curInput['型号'],$InputNum[0]));

            $curPrinterIdArr = Printer::where([
                ['model','like','%'.$result.'%'],
                ['brands_id',$curBrandId],
            ])->pluck('id');
            
            
            //TODO 目前匹配品牌和型号数字开始到结尾的部分
                        

            //待优化，应该可以sql查询处理
            
            foreach($curPrinterIdArr as $curPrinterId)
            {
                if($curPrinterId)
                {
                    $curBindAdapterId = Bind::where('printers_id',$curPrinterId)->pluck('id');

                    $curAdapter = $curInput['系统版本'].'_'.$curInput['系统架构'];

                    if(Empty($curBindAdapterId[0]))
                    {
                        if(Printer::where('id',$curPrinterId)->pluck('onsale')->first() == 0 && $curMatchArr[$i]['解决方案名'] == '暂无适配方案')
                        {
                            $curMatchArr[$i]['解决方案名'] = '已停产型号，暂无适配方案';
                        }
                        elseif(Printer::where('id',$curPrinterId)->pluck('onsale')->first() == 1)
                        {
                            $curPrinterReDate = Printer::where('id',$curPrinterId)->pluck('release_date')->first();
                            $curLocalDate = time();
                            if(($curLocalDate-strtotime($curPrinterReDate)>=157680000000))
                            {
                                $curMatchArr[$i]['解决方案名'] = '发售5年以上机型，暂无适配方案';
                            }
                        }
                    }

                    foreach($curBindAdapterId as $AdapterArr)
                    {
                        $curBindAdapter = Bind::where('id',$AdapterArr)->pluck('adapter')->first();
                        $curBindSolutionId = Bind::where('id',$AdapterArr)->pluck('solutions_id')->first();
                        $curHead = 
                        '已匹配到 ‘'.Brand::where('id',Printer::where('id',$curPrinterId)
                        ->pluck('brands_id')
                        ->first())
                        ->pluck('name')
                        ->first().'’ ‘'.Printer::where('id',$curPrinterId)
                        ->pluck('model')
                        ->first().'’ 型号解决方案：';

                        foreach($curBindAdapter as $value)
                        {
                            if($curAdapter == $value)
                            {
                                if(Bind::where('id',$AdapterArr)->pluck('checked')->first() == 1){$curMatchArr[$i]['适配状态'] = '已验证';}
                                elseif(Bind::where('id',$AdapterArr)->pluck('checked')->first() == 2){$curMatchArr[$i]['适配状态'] = '待验证';}
                                if($curMatchArr[$i]['解决方案名'] == '暂无适配方案')
                                {
                                    $curMatchArr[$i]['解决方案名'] = $curHead.Solution::where('id',$curBindSolutionId)->pluck('name')->first();
                                    $curMatchArr[$i]['解决方案详情'] = Solution::where('id',$curBindSolutionId)->pluck('detail')->first();
                                }
                                else
                                {
                                    $curMatchArr[$i]['解决方案名'] = $curMatchArr[$i]['解决方案名'].'；'.$curHead.Solution::where('id',$curBindSolutionId)->pluck('name')->first();
                                    $curMatchArr[$i]['解决方案详情'] = $curMatchArr[$i]['解决方案详情'].'；'.Solution::where('id',$curBindSolutionId)->pluck('detail')->first();
                                }
                            }
                        }
                        
                    }
                }

                //TODO  考虑将解决方案详情加入一个哈希表，遍历是否有相同内容
                else
                {
                    $curMatchArr[$i]['解决方案名'] = '暂无该型号记录,或核实型号后重新上传';
                }
            }
            ++$i;
        }
        return $curMatchArr;
    }
}