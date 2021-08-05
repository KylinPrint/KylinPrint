<?php

namespace App\Admin\Controllers;

use App\Models\Manufactor;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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
        $grid = new Grid(new Manufactor());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Manufactor name'));
        $grid->column('isconnected', __('Isconnected'));

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
        $show = new Show(Manufactor::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Manufactor name'));
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
        $form = new Form(new Manufactor());

        $form->text('name', __('Manufactor name'));
        $form->number('isconnected', __('Isconnected'));

        return $form;
    }
}