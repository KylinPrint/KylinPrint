<?php

namespace App\Admin\Actions\Form;

use App\Admin\Actions\Exports\PrinterCheckExport;
use App\Admin\Actions\Imports\PrinterCheckImport;
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

class PrinterCheckForm extends Form
{
    public function handle(array $input)
    {
        try {
            //上传文件位置，这里默认是在storage中，如有修改请对应替换
            $file = storage_path('app/public/'.$input['file']);

            //写的导入处理类SolutionMatchImport没tm生效，原因未明
            $array = (Excel::toArray(new PrinterCheckImport,$file))[0];

            // $array = $this->WTF($array);

            $disk = Storage::disk('local');
            $disk -> delete('public'.$input['file']);


            $storeFile = substr(strrchr($input['file'],'/'),1);
            $storeFileName = 'match/'.substr($storeFile,0,strrpos($storeFile,'.')).'_初步验证结果_'.date('Y-m-d_H:i:s').'.xlsx';
            
            (new PrinterCheckExport($array,$file))->store($storeFileName,'admin');

            DB::insert('INSERT INTO printer_checks(title,path) VALUES ("'.$storeFile.'","'.$storeFileName.'")');

            return $this->response()->success('数据导出成功')->refresh();

        } catch (\Exception $e) {
    
            return $this->response()->error($e->getMessage());
        }
    }

    public function form()
    {
        $this->file('file', '上传数据（Excel）')->rules('required', ['required' => '文件不能为空'])->move('/');
    }

}