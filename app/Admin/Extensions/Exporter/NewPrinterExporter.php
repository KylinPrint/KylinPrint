<?php

namespace App\Admin\Extensions\Exporter;

use App\Admin\Extensions\Exporter\BaseExport;
use App\Models\Bind;
use App\Models\Brand;
use App\Models\Industry_Tag;
use App\Models\Industry_Tag_Bind;
use App\Models\Manufactor;
use App\Models\Project_Tag;
use App\Models\Principle_Tag;
use App\Models\Project_Tag_Bind;
use App\Models\Solution;
use Illuminate\Support\Fluent;
use Dcat\Admin\Grid\Exporters\AbstractExporter;
use Dcat\Admin\Http\Displayers\Extensions\Name;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use function PHPUnit\Framework\isEmpty;

class NewPrinterExporter extends BaseExport implements WithMapping, WithHeadings, FromCollection
{
    use Exportable;
    protected $fileName = '表格导出测试';
    protected $titles = [];

    public function __construct()
    {
        $this->fileName = $this->fileName.'_'.Str::random(6).'.xlsx';//拼接下载文件名称
        $this->titles = 
        [   
            '产品ID',
            '厂商名称',
            '产品名称',
            '分类1',
            '分类2',
            '适配系统',
            '芯片',
            '体系架构',
            '兼容等级',
            '测试时间',
            '适配状态',
            '安装包名称',
            '下载地址',
            '产品描述',
            '小版本号',
            '备注',
            '是否计划适配产品',
            '行业',
            '适配类型',
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

        //dd($row);

        $curBindArr = Bind::where('printers_id',$ids)->get()->toArray();
        $ExportArr = array();

        if(isEmpty($curBindArr))
        {
            foreach($curBindArr as $curBind)
            {
                foreach($curBind['adapter'] as $curBindAdapter)
                {
                    $arr = array();
                    $arr['产品ID'] = '';
                    $arr['厂商名称'] = Manufactor::where('id',(Brand::where('id',$row['brands_id'])->pluck('manufactors_id')->first()))->pluck('name')->first();
                    $arr['分类1'] = '输出设备';
                    $arr['分类2'] = Principle_Tag::where('id',$row['principle_tags_id'])->pluck('name')->first();
                    
                    $curCPU = substr(strrchr($curBindAdapter,'_'),1);
                    $curVer = strstr($curBindAdapter,'_',1);

                    switch($curCPU)
                    {
                        case 'Intel/AMD':
                            $arr['适配系统'] = '银河麒麟桌面操作系统（AMD64版）V10';
                            break;
                        case '海光':
                            $arr['适配系统'] = '银河麒麟桌面操作系统（海光版）V10';
                            break;
                        case '兆芯':
                            $arr['适配系统'] = '银河麒麟桌面操作系统（兆芯版）V10';
                            break;
                        case '龙芯MIPS':
                        case '龙芯LoongArch':
                            $arr['适配系统'] = '银河麒麟桌面操作系统（龙芯版）V10';
                            break;
                        case '鲲鹏':
                            $arr['适配系统'] = '银河麒麟桌面操作系统（鲲鹏版）V10';
                            break;
                        case '飞腾':
                            $arr['适配系统'] = '银河麒麟桌面操作系统（飞腾版）V10';
                            break;
                        case '海思麒麟9006c':
                        case '海思麒麟990':
                            $arr['适配系统'] = '银河麒麟桌面操作系统（海思麒麟版）V10';
                            break;
                        case '申威':
                            $arr['适配系统'] = '银河麒麟高级服务器操作系统（申威版）V10';
                            break;
                        default:
                            $arr['适配系统'] = '';
                    }

                    $arr['芯片'] = $curCPU;
                    
                    switch($curCPU)
                    {
                        case 'Intel/AMD':
                        case '海光':   
                        case '兆芯':
                            $arr['体系架构'] = 'X86-64';
                            break;
                        case '龙芯MIPS':
                            $arr['体系架构'] = 'MIPS';
                            break;
                        case '龙芯LoongArch':
                            $arr['体系架构'] = 'LoongArch';
                            break;
                        case '鲲鹏':                   
                        case '飞腾':                         
                        case '海思麒麟9006c':
                        case '海思麒麟990':
                            $arr['体系架构'] = 'ARM64';
                            break;
                        case '申威':
                            $arr['体系架构'] = 'Alpha';
                            break;
                        default:
                            $arr['体系架构'] = '';
                    }

                    switch ($curBind['auth'])
                    {
                        case 1:
                            $arr['兼容等级'] = 'CERTIFICATION';
                            break;
                        case 2:
                            $arr['兼容等级'] = 'READY';
                            break;
                        default:
                            $arr['兼容等级'] = '';
                    }
                    $arr['测试时间'] = strchr($curBind['completion_time'],' ',1);
                    switch($curBind['auth'])
                    {
                        case 1:
                        case 2:
                            $arr['适配状态'] = '通过';
                            break;
                        default:
                            $arr['适配状态'] = '';
                    }
                    $arr['安装包名称'] = Solution::where('id',$curBind['solutions_id'])->pluck('name')->first();

                    $curDetail = Solution::where('id',$curBind['solutions_id'])->pluck('detail')->first();

                    $curDetailCache = substr(strrchr($curDetail,$curCPU),1);

                    $perlStr = '/https[：:]\/\/pan\.baidu\.com\/s\/[^\s]{23}\s*(密|提取)码[：:]?\s*[A-Za-z0-9]{4}|http[：:]\/\/[^\s">]*.deb|https[：:]\/\/www.canon.com.cn\/[^\s]*(\&query\=|\&channel\=[0-9]|\/index)/';

                    // preg_match($PCRE,$curDetailCache,$curURL);
                    preg_match($perlStr,$curDetail,$curURL);

                    $arr['下载地址'] = $curURL?$curURL[0]:'';

                    //dd($arr);

                    $arr['产品描述'] = '';
                    $arr['小版本号'] = $curVer;
                    $arr['备注'] = $curBind['comments'];
                    $arr['是否计划适配产品'] = '';
                    $arr['行业'] = '';
                    $arr['适配类型'] = '';

                    array_push($ExportArr,$arr);
                }
            }
        }

        $IndustryBind_Ids = Industry_Tag_Bind::where('printers_id',$ids)->pluck('industry_tags_id')->toArray();

        $IndustryBind_Names = '';
        foreach($IndustryBind_Ids as $value)
        {
            if($IndustryBind_Names)
            {
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
        foreach($Bind_Adapters as $values)
        {
            foreach($values as $value)
            {
                array_push($Arr,$value);
            }
        }
        $AdapterArr = array();
        foreach ($Arr as $values)
        {
            $a = 0;
            foreach ($AdapterArr as $value)
            {
                if ($AdapterArr == $value){$a++;}
            }
            if ($a == 0){ $AdapterArr = $Arr;}
                $a = 0;
        }
        $AdapterStr = '';
        if (is_array($AdapterArr))
        {
            $AdapterStr = trim(implode(',',$AdapterArr), ',');
        }

        // dd($row);

        $ProjectBind_Ids = Project_Tag_Bind::where('printers_id',$ids)->pluck('project_tags_id')->toArray();
        $ProjectBind_Names = '';
        foreach($ProjectBind_Ids as $value)
        {
            if($ProjectBind_Names)
            {
                $ProjectBind_Names = 
                $ProjectBind_Names.','.Project_Tag::where('id',$value)->pluck('name')->first();
            }
            else{$ProjectBind_Names = Project_Tag::where('id',$value)->pluck('name')->first();}
        }

        return $ExportArr;
    }

    public function getmicrotime()
    {
        list($usec,$sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
    }
}