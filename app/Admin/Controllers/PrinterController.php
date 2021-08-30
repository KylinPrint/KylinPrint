<?php

namespace App\Admin\Controllers;

use App\Models\Printer;
use App\Models\Brand;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Filter\Like;
use Encore\Admin\Grid\Displayers\ContextMenuActions;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use App\Admin\Actions\JumpInfo;
use App\Models\Bind;
use App\Models\Industry_Tag;
use App\Models\Principle_Tag;
use App\Models\Project_Tag;
use App\Models\Printer_Type_Principle_Tag;
use App\Models\Solution;
use Illuminate\Database\Eloquent\Collection;

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
        $grid = new Grid(new Printer());
        //$grid->fixColumns(3);   //冻结前三列

        $grid->setActionClass(ContextMenuActions::class);

        $grid->quickSearch('model');    //快速搜索
       
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
        
            // 在这里添加字段过滤器
            
        });
        $grid->selector(function (Grid\Tools\Selector $selector) {
            
            $selector->select('brands_id', __('Brand'), [
                2 => 'HP',
                1 => 'Canon'
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

            $selector->selectOne('sys','适配系统',[
                'v4' => 'v4','v7' => 'v7','v10' => 'v10','v10sp1' => 'v10sp1'
            ],function($query,$value){
                $this->a = $value;
                $query->whereHas('binds', function ($query) {
                    
                        $query->where('adapter', 'like', "%{$this->a}%");
                      
                });                 
            });
            
            $selector->selectOne('frome','适配架构',[
                'amd' => 'amd','arm' => 'arm','mips' => 'mips','loongarch' => 'loongarch'
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
        $grid->column('onsale', __('Onsale'))->display(function ($onsale) {
            if     ($onsale == '0') { return '<i class="fa fa-close text-red"  ></i>'; }
            elseif ($onsale == '1') { return '<i class="fa fa-check text-green"></i>'; }
            else                    { return ''; }
        });
        $grid->column('network', __('Network'))->display(function ($network) {
            if     ($network == '0') { return '<i class="fa fa-close text-red"  ></i>'; }
            elseif ($network == '1') { return '<i class="fa fa-check text-green"></i>'; }
            else                     { return ''; }
        })->hide();
        $grid->column('duplex', __('Duplex'))->display(function ($duplex) {
            if     ($duplex == 'single') { return '单面'; }
            elseif ($duplex == 'manual') { return '手动双面'; }
            elseif ($duplex == 'duplex') { return '自动双面'; }
            else { return ''; }
        })->hide();
        $grid->column('pagesize', __('Pagesize'))->hide(); 

        $grid->column('adapter_status','适配状态')->display(function ($adapter_status){
            if     ($adapter_status == 0)  { return '未适配'; }
            elseif ($adapter_status == 1) { return '未验证'; }
            elseif ($adapter_status == 2) { return '已验证'; }
        });
        
        $grid->column('binds',__('适配平台'))->display(function($binds){
            $Arr1 = array();
            $Arr2 = array();

            foreach($binds as $value){
                if ($Arr1==null){$Arr1 = $value['adapter'];}
                else {$Arr1 = array_merge($Arr1,$value['adapter']);}
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
        })->label()->hide();

        
        $grid->column('project_tags',__('涉及项目'))->pluck('name')->label();

        $grid->column('solutions', __('Solutions'))->view('admin/printer/solution');

        $grid->column('created_at')->hide()->date('Y-m-d');
        $grid->column('updated_at')->hide()->date('Y-m-d');
        

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
        $show = new Show(Printer::findOrFail($id));

        $show->field('brands.name', __('Brand'));
        $show->model(__('Model'));
        $show->type(__('Printer Type'));

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
        //TODO 解决方案 新增Bind控制器解决新增/修改解决方案
        $show->binds(__('Solutions'), function ($binds) {
            $binds->disableFilter();
            $binds->disableExport();
            $binds->disableCreateButton();
            // $solutions->resource('/admin/comments');
            $binds->column('solutions.name', __('Solution name'));
            $binds->column('solutions.comment', __('Solution comment'));
            $binds->checked(__('Checked'))->display(function ($checked) {
                if     ($checked == '0') { return '未验证'; }
                elseif ($checked == '1') { return '已验证'; }
                else                     { return ''; };
            });
            $binds->column('adapter',__('适配平台'))->label()->filter('like');
            $binds->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();     
                $actions->disableView();
                $actions->add(new JumpInfo($actions->row['id']));

            });

            return $binds;
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
        $form = new Form(new Printer());

        $states = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];

        $form->select('brands_id', __('Brands'))->options(Brand::all()->pluck('name', 'id'));
        $form->text('model', __('Model'));
        $form->select('type', __('Printer Type'))->options(['mono' => 'Mono', 'color' => 'Color']);

        $form->multipleSelect('industry_tags',__('应用行业'))->options(Industry_Tag::all()->pluck('name', 'id'));
        $form->select('principle_tags_id', __('打印机工作方式'))->options(Principle_Tag::all()->pluck('name', 'id'));
        $form->date('release_date', __('Release date'));
        $form->switch('onsale', __('Onsale'))->states($states);
        $form->switch('network', __('Network'))->states($states);
       
        $form->select('duplex', __('Duplex'))->options([
            'single' => __('Single'),
            'manual' => __('Manual Duplex'),
            'duplex' => __('Auto Duplex')
        ]);
        $form->text('pagesize', __('Pagesize'));
        
        $form->multipleSelect('solutions',__('Solution name'))->options(Solution::all()->pluck('name', 'id'));

        $form->hasMany('binds', '适配平台', function (Form\NestedForm $form){
            
            $form->text('solutions_id');

            $form->multipleSelect('adapter')->options([
                'v4_arm' => 'v4_arm','v4_amd' => 'v4_amd','v4_mips' => 'v4_mips',
                'v7_arm' => 'v7_arm','v7_amd' => 'v7_amd','v7_mips' => 'v7_mips',
                'v10_arm' => 'v10_arm','v10_amd' => 'v10_amd','v10_mips' => 'v10_mips',
                'v10sp1_arm' => 'v10sp1_arm','v10sp1_amd' => 'v10sp1_amd','v10sp1_mips' => 'v10sp1_mips',
                'v10sp1_loongarch' => 'v10sp1_loongarch'
            ]);
            $form->select('checked')->options([
                0 => '未验证',1 => '已验证'
            ]);        
        })->disableDelete()->disableCreate()->useTable();
        //无法获取当前实例，回调里面可以

        $form->hidden('adapter_status');

        $form->saved(function (Form $form) {
            if($form->isEditing()){
                $id=request()->route()->parameters()['printer'];
            }//获取当前实例id
            $a = Bind::where('printe
            rs_id', '=', $id)->select('id','checked')->get();
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

        $form->confirm('确定更新吗？', 'edit');
        $form->confirm('确定创建吗？', 'create');
        $form->confirm('确定提交吗？');

        //TODO 解决方案
        return $form;
    }
}
