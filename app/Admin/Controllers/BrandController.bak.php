<?php

namespace App\Admin\Controllers;

use App\Models\Brand;
use App\Models\Manufactor;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;

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
        $grid = new Grid(Brand::with(['manufactors','printers']));

        $grid->column('id', __('ID'))->hide();
        $grid->column('name', __('Brand name'));
        $grid->column('name_en', __('Brand name en'));
        $grid->column('manufactors.name', __('Manufactor'));

        $grid->column('printers', __('Printers Count'))->display(function ($printers) { return count($printers); });

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
        $show = new Show($id, Brand::with(['manufactors','printers']));

        $show->field('id', __('ID'));
        $show->field('name', __('Brand name'));
        $show->field('name_en', __('Brand name en'));
        $show->field('manufactors.name', __('Manufactor'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(Brand::with(['manufactors','printers']));

        $form->text('name', __('Brand name'));
        $form->text('name_en', __('Brand name en'));
        $form->select('manufactors_id', __('Manufactor'))->options(Manufactor::all()->pluck('name', 'id'));

        return $form;
    }
}
