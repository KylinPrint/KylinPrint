<?php

namespace App\Admin\Actions\Exports;

use Dcat\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Printer;
use App\Models\Brand;
use App\Models\Manufactor;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use function PHPUnit\Framework\isEmpty;

class PrinterCheckExport implements FromCollection, WithHeadings, WithStyles
{

    use Exportable;

    public function __construct($array,$file)
    {
        $this->data = $array;

        $this->headings = array_keys($array[0]);
        // [   
        //     '厂商' => '厂商',
        //     'manufacter' => 'manufacter',
        //     '型号' => '型号',
        //     '匹配型号' => '匹配型号',
        //     '备注' => '备注',
        // ];

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

    public function styles(Worksheet $sheet)
    {
        $a = $this->WT($this->data);
        return  $a;
    }

    // public function export()
    // {
    //     $this->download($this->file)->prepare(request())->send();
    //     exit;
    // }

    public function WT($array)
    {
        $curColorRow = array();

        $i = 2;
        foreach($array as $curInput)
        {
            $curBrandId = Brand::where('name_en',$curInput['manufacter(厂商英文名)'])->pluck('id')->first();

            if(!isset($curBrandId)){
                $curColorRow['F'.$i] = ['fill' => ['fillType' => 'linear','startColor' => ['rgb' => 'FF0000'],'endColor' => ['rgb' => 'FF0000']]];
                ++$i;
                continue;
            }

            $curCheck = Printer::where([
                ['model',$curInput['model(英文型号)']],
                ['brands_id',$curBrandId]
            ])->pluck('model')->first();

            if(!isset($curCheck)){
                $curColorRow['F'.$i] = ['fill' => ['fillType' => 'linear','startColor' => ['rgb' => 'FFFF00'],'endColor' => ['rgb' => 'FFFF00']]];
                ++$i;
                continue;
            }

        }

        return $curColorRow;
    }
    
    
    public function WTF($array)
    {

        $curMatchArr = array();
       
        $i = 0;
        foreach($array as $curInput)
        {   
            $curBrandId = Brand::where('name_en',$curInput['manufacter(厂商英文名)'])->pluck('id')->first();

            if(Empty($curBrandId)){
                $curMatchArr[$i] = 
                [
                    '厂商' => $curInput['厂商名称'],
                    'manufacter' => $curInput['manufacter(厂商英文名)'],
                    '型号' => $curInput['model(英文型号)'],
                    '匹配型号' => '暂无',
                    '备注' => '未找到该厂商，尝试核实厂商名称后再查询',
                ];
            }
            else{
                $curCheck = Printer::where([
                    ['model',$curInput['model(英文型号)']],
                    ['brands_id',$curBrandId]
                ])->pluck('model')->first();
    
                if($curCheck){
                    $curMatchArr[$i] = 
                    [
                        '厂商' => $curInput['厂商名称'],
                        'manufacter' => $curInput['manufacter(厂商英文名)'],
                        '型号' => $curInput['model(英文型号)'],
                        '匹配型号' => '完全匹配',
                        '备注' => '完全匹配',
                    ];
                }else{
                    preg_match('/\d+/',$curInput['model(英文型号)'],$InputNum);
                    $result = substr($curInput['model(英文型号)'],strripos($curInput['model(英文型号)'],$InputNum[0]));
        
                    $curModelArr = Printer::where([
                        ['model','like','%'.$result.'%'],
                        ['brands_id',$curBrandId],
                    ])->pluck('model');
                    
                    $curModelStr = '';
        
                    foreach($curModelArr as $curModel){
                        if(isEmpty($curModelStr)){$curModelStr = '‘'.$curModel.'’';}
                        else{$curModelStr = $curModelStr.','.'‘'.$curModel.'’';}
                    }
        
                    $curMatchArr[$i] = 
                    [
                        '厂商' => $curInput['厂商名称'],
                        'manufacter' => $curInput['manufacter(厂商英文名)'],
                        '型号' => $curInput['model(英文型号)'],
                        '匹配型号' => $curModelStr?:'未找到匹配型号，尝试核实型号后再查询',
                        '备注' => '',
                    ];
                }
                
            }
                
            $i++;
        }
        return $curMatchArr;
    }
}