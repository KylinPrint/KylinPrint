<?php

namespace App\Admin\Controllers;

use App\Models\Brand;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BrandController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Brand';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Brand());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('name_en', __('Name en'));
        $grid->column('manufactor_id', __('Manufactor id'));

        $grid->column('printers', 'Printers')->display(function ($printers) {
            $count = count($printers);
            return "<span class='label label-warning'>{$count}</span>";
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
        $show = new Show(Brand::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('brand_name', __('Brand name'));
        $show->field('brand_name_en', __('Brand name en'));
        $show->field('manufactor_id', __('Manufactor id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Brand());

        $form->text('brand_name', __('Brand name'));
        $form->text('brand_name_en', __('Brand name en'));
        $form->number('manufactor_id', __('Manufactor id'));

        return $form;
    }
}
