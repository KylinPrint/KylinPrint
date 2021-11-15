<?php

namespace App\Admin\Controllers;

use App\Models\Printer;
use App\Models\Brand;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Admin\Repositories\Report;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Admin;
use App\Admin\Renderable\ProjectTable;
use Dcat\Admin\Widgets\Modal;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Grid\Filter\Like;
use Dcat\Admin\Grid\Displayers\ContextMenuActions;
use Dcat\Admin\Show;
use App\Admin\Actions\Modal\PrinterModal;
use App\Admin\Actions\JumpInfo;
use App\Models\Bind;
use App\Models\Industry_Tag;
use App\Models\Principle_Tag;
use App\Models\Solution;
use Dcat\Admin\Http\Auth\Permission;
use Illuminate\Database\Eloquent\Collection;
use App\Admin\Extensions\Exporter\PrinterExporter;
use App\Models\Industry_Tag_Bind;
use App\Models\Project_Tag_Bind;

use function Doctrine\StaticAnalysis\DBAL\makeMeACustomConnection;

class PrinterController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '打印机';


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(Printer::with(['brands','solutions','binds','industry_tags','principle_tags','project_tags']));
        //$grid->fixColumns(3);   //冻结前三列

        $grid->setActionClass(ContextMenuActions::class);
        $grid->showColumnSelector();
        if(!Admin::user()->can('create-printers')){
            $grid->disableCreateButton();
        }
        
        $grid->export(new PrinterExporter());

        $grid->quickSearch('model');    //快速搜索
       
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
        
            // 在这里添加字段过滤器
                       
        });

        if(Admin::user()->can('edit-printers')){
            $grid->tools(function  (Grid\Tools  $tools)  { 
                //Excel导入
                $tools->append(new PrinterModal()); 
            });
        }
        

        $grid->selector(function (Grid\Tools\Selector $selector) {
            
            $selector->select('brands_id', __('Brand'), [
                149 => '立象',156 => '兄弟',157 => '佳能',165 => '得实',170 => '爱普生',
                173 => '富士施乐',174 => '富士通',175 => '佳博',187 => '惠普',195 => '柯尼卡美能达',
                196 => '京瓷',199 => '联想',200 => '利盟',203 => '南天',217 => '理光',
                222 => '三星',225 => '夏普',234 => '光电通',235 => '东芝',236 => '天津国聚',
            ]);
            $selector->select('onsale', __('Onsale'), [
                1 => '在售',
                0 => '停产'
            ]);

            $selector->select('adapter_status', __('适配状态'), [
                0 => '未适配',
                1 => '未验证',
                2 => '已验证'
            ]);

            //TODO 查V10会把V10SP1也输出
            $selector->selectOne('sys','适配系统',[
                'V4_' => 'V4','V7_' => 'V7','V10_' => 'V10','V10SP1_' => 'V10SP1'
            ],function($query,$value){
                $this->a = $value;
                $query->whereHas('binds', function ($query) {
                    
                        $query->where('adapter', 'like', "%{$this->a}%");
                        $curQuery = $query;
                      
                });
            });
            
            $selector->selectOne('frome','适配架构',[
                '_X86' => 'X86','_ARM' => 'ARM','_MIPS' => 'MIPS','_LoongAarch' => 'LoongAarch'
            ],function($query,$value){
                $this->b = $value;
                $query->whereHas('binds', function ($query) {
                    $query->where('adapter', 'like', "%{$this->b}%");
                });                 
            });
                    
        }); //表头过滤
        
        $grid->column('id', __('ID'))->sortable()->hide();

        //TODO 一排序就炸
        $grid->column('brands.name', __('Brand'))->sortable()->help('CCC');
        $grid->column('model', __('Model'))->sortable();
        $grid->column('type', __('Printer Type'))->display(function ($printerType) {
            if     ($printerType == 'mono')  { return '黑白'; }
            elseif ($printerType == 'color') { return '彩色'; }
            else                             { return ''; }
        });


        $grid->column('industry_tags',__('应用行业'))->pluck('name')->label();
        $grid->column('principle_tags.name', __('打印机工作方式'));
        $grid->column('release_date', __('Release date'));
        $grid->column('onsale', __('在售'))->display(function ($onsale) {
            if     ($onsale == '0') { return '<i class="fa fa-close text-red"  ></i>'; }
            elseif ($onsale == '1') { return '<i class="fa fa-check text-green"></i>'; }
            else                    { return '<i class="fa fa-clock-o text-black"></i>'; }
        });
        $grid->column('network', __('网络功能'))->display(function ($network) {
            if     ($network == '0') { return '<i class="fa fa-close text-red"  ></i>'; }
            elseif ($network == '1') { return '<i class="fa fa-check text-green"></i>'; }
            else                     { return '<i class="fa fa-clock-o text-black"></i>'; }
        })->hide();
        $grid->column('duplex', __('双面'))->display(function ($duplex) {
            if     ($duplex == 'single') { return '单面'; }
            elseif ($duplex == 'manual') { return '手动双面'; }
            elseif ($duplex == 'duplex') { return '自动双面'; }
            else { return ''; }
        })->hide();
        $grid->column('pagesize', __('最大幅面'))->hide(); 

        $grid->column('adapter_status','适配状态')->display(function ($adapter_status){
            if     ($adapter_status == 0)  { return '未适配'; }
            elseif ($adapter_status == 1) { return '未验证'; }
            elseif ($adapter_status == 2) { return '已验证'; }
        })->dot(
            [
                0 => 'danger',
                1 => 'primary',
                2 => 'success',
            ], 
            'danger' // 默认值
        );
    
        //TODO 需加空验证
        $grid->column('binds',__('适配平台'))->display(function($binds){
            $Arr1 = array();
            $Arr2 = array();
            if(!isset($binds[0])){
                foreach($binds as $value){
                    if ($Arr1==null){$Arr1 = $value['adapter'];}
                    else {$Arr1 = array_merge($Arr1,$value['adapter']);}
                    //数组合并，用+会覆盖，暂不清楚原因
                }
               
                foreach ($Arr1 as $value){
                    $a = 0;
                    foreach ($Arr2 as $value){
                        if ($Arr2 == $value){$a++;}
                    }
                    if ($a == 0){ $Arr2 = $Arr1;}
                        $a = 0;
                }
                return $Arr2; 
            }
            else return '';
            
        })->label()->hide();
        //判断条件待优化

        
        // $grid->column('project_tags',__('涉及项目'))->pluck('name')->label();

        $grid->column('project_tags',__('涉及项目'))->display('详情')->modal(function ($modal) {
            $modal->title('涉及项目');
        
            return ProjectTable::make(['title' => $this->title]);
        });
        

        $grid->column('solutions', __('Solutions'))->view('admin/printer/solution');

        $grid->column('created_at')->hide();

        //包括关联表的更新时间取最新
        $grid->column('update')->display(function(){
            $id = $this->id;
        
        $CurBindUpDate_AT_Exist = Bind::where("printers_id",$id)->exists();
        $CurIndustryUpDate_AT_Exist = Industry_Tag_Bind::where("printers_id",$id)->exists();
        $CurProjectUpDate_AT_Exist = Project_Tag_Bind::where("printers_id",$id)->exists();
    
        $UpdateArr = array();
    
        $UpdateArr[] = Printer::where("id",$id)->pluck('updated_at')->first()->timestamp;
    
        if($CurBindUpDate_AT_Exist)
        {
            $CurBindUpDate_AT = Bind::where("printers_id",$id)->pluck('updated_at');
            foreach ($CurBindUpDate_AT as $value)
            {
                array_push($UpdateArr,($value->timestamp));
            }
        }
    
        if($CurIndustryUpDate_AT_Exist)
        {
            $CurIndustryUpDate_AT = Industry_Tag_Bind::where("printers_id",$id)->pluck('updated_at');
            foreach ($CurIndustryUpDate_AT as $value)
            {
                $a = $value;
                array_push($UpdateArr,($value->timestamp));
            }
        }
        if($CurProjectUpDate_AT_Exist)
        {
            $CurProjectUpDate_AT = Project_Tag_Bind::where("printers_id",$id)->pluck('updated_at');
            foreach ($CurProjectUpDate_AT as $value)
            {
                array_push($UpdateArr,($value->timestamp));
            }
        }
        
        return date("Y-m-d H:i",max($UpdateArr));
        });

        $grid->column('updated_at')->hide();
        

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {

        $show = new Show($id, Printer::with(['brands','solutions','binds','industry_tags','principle_tags']));

        if(!Admin::user()->can('edit-printers')){
            $show->panel()->tools(function ($tools) 
            {
                $tools->disableEdit();
                $tools->disableDelete();
            });    
        }

        $show->field('brands.name', __('Brand'));
        $show->field('model');
        
        $show->field('industry_tags',__('应用行业'))->as(function($industry_tags){
            $IndustryArr = array();
            foreach($industry_tags as $value){$IndustryArr[] = $value['name'];}
            return $IndustryArr;
        })->label();
        $show->field('principle_tags.name', __('打印机工作方式'));
        $show->release_date(__('Release date'));
        $show->onsale(__('Onsale'));
        $show->network(__('Network'));
        $show->duplex(__('Duplex'));
        $show->pagesize(__('Pagesize'));
        // //TODO 解决方案 新增Bind控制器解决新增/修改解决方案
        $show->binds(__('Solutions'), function ($model) {
            $grid = new Grid(Bind::with(['printers','solutions']));

            $grid->model()->where('printers_id', $model->id);
            $grid->disableFilter();
            //$grid->disableExport();
            $grid->disableCreateButton();
            $grid->setResource('/admin/comments');
            $grid->column('solutions.name', __('Solution name'));
            $grid->column('solutions.comment', __('Solution comment'));
            $grid->checked(__('Checked'))->display(function ($checked) {
                if     ($checked == '0') { return '未验证'; }
                elseif ($checked == '1') { return '已验证'; }
                else                     { return ''; };
            });
            $grid->column('adapter',__('适配平台'))->label();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();     
                $actions->disableView();
                $actions->append(new JumpInfo($actions->row['id']));

            });

            return $grid;
        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        return Form::make(Printer::with(['brands','solutions','binds','industry_tags']), function (Form $form){
            $states = [
                'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
            ];
            
            $form->select('brands_id', __('Brands'))->options(Brand::all()->pluck('name', 'id'));
            $form->text('model', __('Model'));
            $form->select('type', __('Printer Type'))->options(['mono' => 'Mono', 'color' => 'Color']);
            

            $form->multipleSelect('industry_tags',__('应用行业'))
            ->options(Industry_Tag::all()->pluck('name', 'id'))
            ->customFormat(function ($v) {
                if (! $v) {
                    return [];
                }
    
                // 从数据库中查出的二维数组中转化成ID
                return array_column($v, 'id');
            });
            $form->select('principle_tags_id', __('打印机工作方式'))->options(Principle_Tag::all()->pluck('name', 'id'));
            $form->date('release_date', __('Release date'));
            $form->switch('onsale', __('Onsale'))->options($states);
            $form->switch('network', __('Network'))->options($states);
           
            $form->select('duplex', __('Duplex'))->options([
                'single' => __('Single'),
                'manual' => __('Manual Duplex'),
                'duplex' => __('Auto Duplex')
            ]);
            $form->text('pagesize', __('Pagesize'));
    
            //TODO 当solution删除时不删除对应bind而是将solution_id删除，待改
            $form->multipleSelect('solutions',__('Solution name'))
            ->options(Solution::all()->pluck('name', 'id'))
            ->customFormat(function ($v) {
                if (! $v) {
                    return [];
                }

                // 从数据库中查出的二维数组中转化成ID
                return array_column($v, 'id');
            });

            //TODO 导致上个多选框删除时只删除bind中solution_id字段，原因未明,与上同一个问题
            if (!$form->isCreating()){
                $form->hasMany('binds', '适配平台', function (Form\NestedForm $form){

                    $form->text('solution_name', '解决方案名')->disable()->customFormat(function ($v) {
                        $a = $this->toArray();
                        $b = Solution::where('id',$a['solutions_id'])->pluck('name')->first();
                        return $b;
                    });
                      
                    $form->multipleSelect('adapter')->options([
                        'V4_ARM' => 'V4_ARM','V4_X86' => 'V4_X86','V4_MIPS' => 'V4_MIPS',
                        'V7_ARM' => 'V7_ARM','V7_X86' => 'V7_X86','V7_MIPS' => 'V7_MIPS',
                        'V10_ARM' => 'V10_ARM','V10_X86' => 'V10_X86','V10_MIPS' => 'V10_MIPS',
                        'V10SP1_ARM' => 'V10SP1_ARM','V10SP1_X86' => 'V10SP1_X86','V10SP1_MIPS' => 'V10SP1_MIPS',
                        'V10SP1_LoongArch' => 'V10SP1_LoongArch'
                    ]);
                    $form->select('checked')->options([
                        0 => '未验证',1 => '已验证'
                    ]);        
                })->disableDelete()->disableCreate()->useTable();
            }    
    
            $form->hidden('adapter_status');

            if (!$form->isCreating()){
                $form->saving(function (Form $form) {
                    if($form->isEditing()){
                        $id = request()->route()->parameters()['printer'];
                    }//获取当前实例id
                    $a = Bind::where('printers_id', '=', $id)->select('id','checked')->get();
                    $count = count($a);
                    if($count == 0){$form->adapter_status = 0;}
                    else {
                        $b = 0;
                        foreach ($a as $value){
                            if ($value['checked'] == 1) {$b++;}
                        }
                        if ($b == 0) {$form->adapter_status = 1;}
                        else $form->adapter_status = 2;
                    };
                }); 
            }
            else{$form->adapter_status = 0;}
            //待优化
                       
    
            $form->confirm('确定更新吗？', 'edit');
            $form->confirm('确定创建吗？', 'create');
            $form->confirm('确定提交吗？');
        });       

        //TODO 解决方案

    }

    public function UpdateFilter($request){
        $id = $request->id;
        
        $CurBindUpDate_AT_Exist = Bind::where("printers_id",$id)->exists();
        $CurIndustryUpDate_AT_Exist = Industry_Tag_Bind::where("printers_id",$id)->exists();
        $CurProjectUpDate_AT_Exist = Project_Tag_Bind::where("printers_id",$id)->exists();
    
        $UpdateArr = array();
    
        $UpdateArr[] = Printer::where("id",$id)->pluck('updated_at')->first()->timestamp;
    
        if($CurBindUpDate_AT_Exist)
        {
            $CurBindUpDate_AT = Bind::where("printers_id",$id)->pluck('updated_at');
            foreach ($CurBindUpDate_AT as $value)
            {
                array_push($UpdateArr,($value->timestamp));
            }
        }
    
        if($CurIndustryUpDate_AT_Exist)
        {
            $CurIndustryUpDate_AT = Industry_Tag_Bind::where("printers_id",$id)->pluck('updated_at');
            foreach ($CurIndustryUpDate_AT as $value)
            {
                $a = $value;
                array_push($UpdateArr,($value->timestamp));
            }
        }
        if($CurProjectUpDate_AT_Exist)
        {
            $CurProjectUpDate_AT = Project_Tag_Bind::where("printers_id",$id)->pluck('updated_at');
            foreach ($CurProjectUpDate_AT as $value)
            {
                array_push($UpdateArr,($value->timestamp));
            }
        }
        
        return date("Y-m-d H:i",max($UpdateArr));
    
    }
}

