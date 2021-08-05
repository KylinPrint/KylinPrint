<?php

namespace App\Admin\Controllers;

use App\Models\Solution;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SolutionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Solution';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Solution());

        $grid->column('id', __('Id'));
        $grid->column('solution_name', __('Solution name'));
        $grid->column('solution_comment', __('Solution comment'));
        $grid->column('solution_source', __('Solution source'));

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
        $show = new Show(Solution::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('solution_name', __('Solution name'));
        $show->field('solution_comment', __('Solution comment'));
        $show->field('solution_source', __('Solution source'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Solution());

        $form->text('solution_name', __('Solution name'));
        $form->text('solution_comment', __('Solution comment'));
        $form->text('solution_source', __('Solution source'));

        return $form;
    }
}
