<?php

namespace App\Admin\Controllers;

use App\Models\Printer;
use App\Models\Brand;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Filter\Like;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

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

        $grid->quickSearch('model');    //快速搜索
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
        
            // 在这里添加字段过滤器
            
        });
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('brands_id', __('Brand'), [
                1 => 'HP',
                2 => 'Canon',
            ]);
            $selector->select('onsale', __('Onsale'), [
                1 => '在售',
                0 => '停产',
            ]);
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
        });
        $grid->column('duplex', __('Duplex'))->display(function ($duplex) {
            if     ($duplex == 'single') { return '单面'; }
            elseif ($duplex == 'manual') { return '手动双面'; }
            elseif ($duplex == 'duplex') { return '自动双面'; }
            else { return ''; }
        });
        $grid->column('pagesize', __('Pagesize'));

        $grid->column('solutions', __('Solutions'))->view('admin/printer/solution');

        $grid->column('created_at')->hide()->date('Y-m-d');
        $grid->column('updated_at')->hide()->date('Y-m-d');

        $grid->export(function ($export) {
            $export->originalValue(['onsale', 'network']);
            $export->column('solutions', function ($value, $original) {
                $ret = array();
                foreach ($original as $key => $value) {
                    array_push($ret, $value['name']);
                }
                return implode(',', $ret);
            });
        });

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
        $show->release_date(__('Release date'));
        $show->onsale(__('Onsale'));
        $show->network(__('Network'));
        $show->duplex(__('Duplex'));
        $show->pagesize(__('Pagesize'));
        //TODO 解决方案 新增Bind控制器解决新增/修改解决方案
        $show->binds(__('Solutions'), function ($binds) {
            $binds->disableFilter();
            // $solutions->resource('/admin/comments');
            $binds->column('solutions.name', __('Solution name'));
            $binds->column('solutions.comment', __('Solution comment'));
            $binds->checked(__('Checked'))->display(function ($checked) {
                if     ($checked == '0') { return '<i class="fa fa-close text-red"  ></i>'; }
                elseif ($checked == '1') { return '<i class="fa fa-check text-green"></i>'; }
                else                     { return ''; };
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
        $form->date('release_date', __('Release date'));
        $form->switch('onsale', __('Onsale'))->states($states);
        $form->switch('network', __('Network'))->states($states);
        $form->select('duplex', __('Duplex'))->options([
            'single' => __('Single'),
            'manual' => __('Manual Duplex'),
            'duplex' => __('Auto Duplex')
        ]);
        $form->text('pagesize', __('Pagesize'));

        $form->confirm('确定更新吗？', 'edit');
        $form->confirm('确定创建吗？', 'create');
        $form->confirm('确定提交吗？');

        //TODO 解决方案
        return $form;
    }
}
