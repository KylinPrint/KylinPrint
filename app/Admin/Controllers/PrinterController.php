<?php

namespace App\Admin\Controllers;

use App\Models\Printer;
use App\Models\Brand;
use App\Models\Manufactor;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
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
        $grid->fixColumns(3);   //冻结前三列

        $grid->column('id', __('ID'))->sortable()->hide();

        //TODO 一排序就炸
        $grid->column('brands.name', __('Brand'))->sortable()->help('CCC');
        $grid->column('model', __('Model'))->sortable();
        $grid->column('type', __('Printer Type'))->display(function ($printerType) {
            return $printerType ? '黑白' : '彩色';
        });
        $grid->column('release_date', __('Release date'))->default(date('YY-mm-dd'));
        $grid->column('onsale', __('Onsale'))->bool();
        $grid->column('network', __('Network'))->bool();
        $grid->column('duplex', __('Duplex'))->display(function ($duplex) {
            if     ($duplex == 'single') { return '单面'; }
            elseif ($duplex == 'manual') { return '手动双面'; }
            else                         { return '自动双面'; }
        });
        $grid->column('pagesize', __('Pagesize'));

        $grid->column('solutions', __('Solutions'))
            ->display(function ($solutions) { return count($solutions); })
            ->modal('解决方案', function ($model) {
                $solutions = $model->solutions()->take(10)->get()->map(function ($comment) {
                    return $comment->only(['name', 'comment']);
                });

                return new Table(['名称', '摘要'], $solutions->toArray());
        });

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

        $show->field('id', __('ID'));
        $show->field('brands.name', __('Brand'));
        $show->field('model', __('Model'));
        $show->field('type', __('Printer Type'));
        $show->field('release_date', __('Release date'));
        $show->field('onsale', __('Onsale'));
        $show->field('network', __('Network'));
        $show->field('duplex', __('Duplex'));
        $show->field('pagesize', __('Pagesize'));

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
        $form->date('release_date', __('Release date'))->default(date('YY-mm-dd'));
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

        return $form;
    }
}
