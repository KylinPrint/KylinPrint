<?php

namespace App\Admin\Controllers;

use App\Models\Manufactor;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;

class ManufactorController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Manufactor';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(Manufactor::with(['brands']));

        $grid->column('id', __('ID'))->hide();
        $grid->column('name', __('Manufactor'));
        $grid->column('isconnected', __('Isconnected'))->bool();

        $grid->column('brands', __('Brands Count'))->display(function ($brands) { return count($brands); });

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
        $show = new Show($id, Manufactor::with(['brands']));

        $show->field('id', __('ID'));
        $show->field('name', __('Manufactor'));
        $show->field('isconnected', __('Isconnected'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(Manufactor::with(['brands']));

        $form->text('name', __('Manufactor'));
        $form->number('isconnected', __('Isconnected'));

        return $form;
    }
}
