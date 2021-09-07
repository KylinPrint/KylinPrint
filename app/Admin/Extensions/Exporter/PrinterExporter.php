<?php

namespace App\Admin\Extensions\Exporter;

use App\Admin\Extensions\Exporter\BaseExport;
use App\Models\Bind;
use App\Models\Industry_Tag;
use App\Models\Industry_Tag_Bind;
use App\Models\Project_Tag;
use App\Models\Project_Tag_Bind;
use Illuminate\Support\Fluent;
use Dcat\Admin\Grid\Exporters\AbstractExporter;
use Dcat\Admin\Http\Displayers\Extensions\Name;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrinterExporter extends BaseExport implements WithMapping, WithHeadings, FromCollection
{
    use Exportable;
    protected $fileName = '表格导出测试';
    protected $titles = [];

    public function __construct()
    {
        $this->fileName = $this->fileName.'_'.Str::random(6).'.xlsx';//拼接下载文件名称
        $this->titles = 
        [   
            'id' => 'ID',
            'brands_id' => '品牌',
            'model' => '打印机型号',
            'type' => '彩打功能',
            'industry_tags_name'=> '应用行业',
            'principle_tags_id' => '打印机工作方式',
            'release_date' => '发售日期',
            'onsale' => '在售',
            'network' => '网络功能',
            'duplex' => '双面打印',
            'pagesize' => '支持最大幅面',
            'adapter_status' => '适配状态',
            'bind_adapters'=> '适配平台',
            'project_tags_name'=> '涉及项目',
            'created_at' => '创建时间',
            'updated_at' => '更新时间'
        ];
        parent::__construct();
    }

    public function export()
    {
        // TODO: Implement export() method.
        $this->download($this->fileName)->prepare(request())->send();
        exit;
    }

    public function collection()
    {
        // TODO: Implement collection() method.

        return collect($this->buildData());
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return $this->titles();
    }

    public function map($row): array
    {

        // TODO: Implement map() method.
        
        $PrinterRow = new Fluent($row);
        $ids = $PrinterRow->id;
        
        $IndustryBind_Ids = Industry_Tag_Bind::where('printers_id',$ids)->pluck('industry_tags_id')->toArray();
        $IndustryBind_Names = '';
        foreach($IndustryBind_Ids as $value){
            if($IndustryBind_Names){
                $IndustryBind_Names = 
                $IndustryBind_Names.','.Industry_Tag::where('id',$value)->pluck('name')->first();
            }
            else{$IndustryBind_Names = Industry_Tag::where('id',$value)->pluck('name')->first();}
        }

        $network = $PrinterRow->network;
        $network_statu = '';
        if($network){$network_statu = '支持';}
        else{$network_statu = '不支持';}

        $Bind_Adapters = Bind::where('printers_id',$ids)->pluck('adapter')->toArray();
        $Arr = array();
        foreach($Bind_Adapters as $values){
            foreach($values as $value){
                array_push($Arr,$value);
            }
        }
        $AdapterArr = array();
        foreach ($Arr as $values){
            $a = 0;
            foreach ($AdapterArr as $value){
                if ($AdapterArr == $value){$a++;}
            }
            if ($a == 0){ $AdapterArr = $Arr;}
                $a = 0;
        }
        $AdapterStr = '';
        if (is_array($AdapterArr)){
            $AdapterStr = trim(implode(',',$AdapterArr), ',');
        }

        // dd($row);

        $ProjectBind_Ids = Project_Tag_Bind::where('printers_id',$ids)->pluck('project_tags_id')->toArray();
        $ProjectBind_Names = '';
        foreach($ProjectBind_Ids as $value){
            if($ProjectBind_Names){
                $ProjectBind_Names = 
                $ProjectBind_Names.','.Project_Tag::where('id',$value)->pluck('name')->first();
            }
            else{$ProjectBind_Names = Project_Tag::where('id',$value)->pluck('name')->first();}
        }

        return [
            $row['id'],
            $row['brands.name'],
            $row['model'],
            $row['type'],
            $row['industry_tags_name'] = $IndustryBind_Names,
            $row['principle_tags.name'],
            $row['release_date'],
            $row['onsale'],
            $row['network'] = $network_statu,
            $row['duplex'],
            $row['pagesize'],
            $row['adapter_status'],
            $row['bind_adapters'] = $AdapterStr,
            $row['project_tags_name'] = $ProjectBind_Names,
            $row['created_at'],
            $row['updated_at'],
        ];
    }
}