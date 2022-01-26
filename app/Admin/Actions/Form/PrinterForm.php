<?php

namespace App\Admin\Actions\Form;

use App\Admin\Actions\Imports\PrinterImport;
use App\Admin\Actions\Imports\SolutionImport;
use App\Admin\Actions\Imports\BindImport;
use App\Admin\Actions\Imports\BindUpdate;
use App\Admin\Actions\Imports\BrandImport;
use App\Admin\Actions\Imports\ManufactorImport;
use App\Models\Solution;
use Dcat\Admin\Widgets\Form;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

ini_set('max_execution_time', 600);
ini_set('upload_max_filesize', '10M');

class PrinterForm extends Form
{
    public function handle(array $input)
    {
        try {
            //上传文件位置，这里默认是在storage中，如有修改请对应替换
            $file = storage_path('app/public/' . $input['file']);

            // Excel::import(new ManufactorImport(),$file);
            // Excel::import(new BrandImport(),$file);
            // Excel::import(new PrinterImport(),$file);
            // Excel::import(new SolutionImport(),$file);
            Excel::import(new BindImport(),$file);
            // Excel::import(new BindUpdate(),$file);

            $disk = Storage::disk('public');
            $disk -> delete($input['file']);
            
            return $this->response()->success('数据导入成功')->refresh();
        } catch (\Exception $e) {
            return $this->response()->error($e->getMessage());
        }
    }

    public function form()
    {
        $this->file('file', '上传数据（Excel）')->rules('required', ['required' => '文件不能为空'])->move('admin/upload');

    }

}