<?php

namespace App\Admin\Controllers;

use App\Models\Printer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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

        $grid->column('brands.name', __('Brand'))->sortable()->help('CCC');
        $grid->column('model', __('Model'))->sortable();
        $grid->column('manufactors.name', __('Manufactor'))->hide();
        $grid->column('type', 'Type')->display(function ($printerType) {
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
        $show->field('brands_id', __('Brand id'));
        $show->field('model', __('Model'));
        $show->field('manufactors_id', __('Manufactor id'));
        $show->field('type', __('Type'));
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

        $form->number('brands_id', __('Brand id'));
        $form->text('model', __('Model'));
        $form->number('manufactors_id', __('Manufactor id'));
        $form->text('type', __('Type'));
        $form->date('release_date', __('Release date'))->default(date('YY-mm-dd'));
        $form->number('onsale', __('Onsale'));
        $form->number('network', __('Network'));
        $form->text('duplex', __('Duplex'));
        $form->text('pagesize', __('Pagesize'));

        return $form;
    }
}
