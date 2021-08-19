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

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('source', __('解决方案来源'), [
                1 => 'HP',
                2 => 'Canon',
                3 => 'Kylin'
            ]);
            
        }); 

        $grid->column('id', __('ID'))->hide();
        $grid->column('name', __('Solution name'));
        $grid->column('comment', __('Solution comment'));
        $grid->column('source', __('Solution source'));
        $grid->column('amd64', __('amd64'))->bool();
        $grid->column('arm64', __('arm64'))->bool();
        $grid->column('mips64el', __('mips64el'))->bool();
        $grid->column('loongarch64', __('loongarch64'))->bool();

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

        $show->field('id', __('ID'));
        $show->field('name', __('Solution name'));
        $show->field('comment', __('Solution comment'));
        $show->field('source', __('Solution source'));
        $show->detail()->unescape()->as(function($detail){ 
            return $detail;
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
        $form = new Form(new Solution());

        $form->text('name', __('Solution name'));
        $form->text('comment', __('Solution comment'));
        $form->text('source', __('Solution source'));
        $form->editor('detail');

        return $form;
    }
}
