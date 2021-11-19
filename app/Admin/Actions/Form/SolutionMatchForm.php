<?php

namespace App\Admin\Actions\Form;

use App\Admin\Actions\Exports\SolutionMatchExport;
use App\Admin\Actions\Imports\SolutionMatchImport;
use App\Models\Solution;
use App\Models\Printer;
use App\Models\Bind;
use App\Models\Brand;
use Dcat\Admin\Widgets\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isEmpty;

ini_set('max_execution_time', 600);
ini_set('upload_max_filesize', '8M');
date_default_timezone_set('PRC');

class SolutionMatchForm extends Form
{
    public function handle(array $input)
    {
        try {
            //上传文件位置，这里默认是在storage中，如有修改请对应替换
            $file = storage_path('app/public/'.$input['file']);

            //写的导入处理类SolutionMatchImport没tm生效，原因未明
            $array = (Excel::toArray(new SolutionMatchImport,$file))[0];
            $array = $this->WTF($array);

            $disk = Storage::disk('local');
            $disk -> delete('public'.$input['file']);

            $storeFile = substr(strrchr($input['file'],'/'),1);
            $storeFileName = 'match/'.substr($storeFile,0,strrpos($storeFile,'.')).'_匹配方案_'.date('Y-m-d_H:i:s').'.xlsx';
            
            (new SolutionMatchExport($array,$file))->store($storeFileName,'admin');

            DB::insert('INSERT INTO solution_matches(title,path) VALUES ("'.$storeFile.'","'.$storeFileName.'")');

            return $this->response()->success('数据导出成功')->refresh();

        } catch (\Exception $e) {
    
            return $this->response()->error($e->getMessage()); 
        }
    }

    public function form()
    {
        $this->file('file', '上传数据（Excel）')->rules('required', ['required' => '文件不能为空'])->move('/');
    }

    public function WTF($array)
    {
        $curMatchArr = array();
        $curMatchArr[0] = 
        [
            '厂商' => '厂商',
            '型号' => '型号',
            '系统版本' => '系统版本',
            '系统架构' => '系统架构',
            '解决方案名' => '解决方案名',
            '解决方案详情' => '解决方案详情',
            '适配状态' => '适配状态'
        ];
        $i = 1;

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
            preg_match('/\d+/',$curInput['型号'],$InputNum);

            $curPrinterIdArr = Printer::where('model','like','%'.$InputNum[0].'%')->pluck('id');
            $curPrinterArr = array();
            //TODO 目前仅匹配厂商和型号数字，考虑如何优化型号匹配
            
            foreach($curPrinterIdArr as $pid)
            {
                preg_match('/\d+/',Printer::where('id',$pid)->pluck('model')->first(),$curPrinterNum);
                if($curPrinterNum[0] == $InputNum[0])
                {
                    if($curInput['厂商'])
                    {
                        $curBrandId = Printer::where('id',$pid)->pluck('brands_id')->first();
                        if(Brand::where('id',$curBrandId)->pluck('name')->first() == $curInput['厂商'] || Brand::where('id',$curBrandId)->pluck('name_en')->first() == $curInput['厂商'])
                        {
                            array_push($curPrinterArr,$pid);
                        }
                    }
                    else{array_push($curPrinterArr,$pid);}
                }
            }

            //待优化，应该可以sql查询处理
            
            foreach($curPrinterArr as $curPrinterId)
            {
                if($curPrinterId)
                {
                    $curBindAdapterId = Bind::where('printers_id',$curPrinterId)->pluck('id');

                    $curAdapter = $curInput['系统版本'].'_'.$curInput['系统架构'];

                    if(isEmpty($curBindAdapterId))
                    {
                        if(Printer::where('id',$curPrinterId)->pluck('onsale')->first() == 0)
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
                else
                {
                    $curMatchArr[$i]['解决方案名'] = '暂无该型号记录,或核实型号后重新上传';
                }
            }
            $i++;
        }
        return $curMatchArr;
    }

}