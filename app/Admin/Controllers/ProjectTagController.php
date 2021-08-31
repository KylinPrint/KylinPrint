<?php

namespace App\Admin\Controllers;

use App\Models\Project_Tag;
use App\Models\Project_Tag_Bind;
use App\Models\Printer;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;

class ProjectTagController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Project_Tag(['printers','project_tag_binds']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show($id, Project_Tag::with(['printers','project_tag_binds']));

        $show->field('id');
        $show->field('name');
        
        $show->project_tag_binds(__('Printer'), function ($model) {
            $grid = new Grid(Project_Tag_Bind::with(['printers','project_tags']));

            $grid->model()->where('project_tags_id', $model->id);
            $grid->disableFilter();
            //$grid->disableExport();
            $grid->disableCreateButton();
            $grid->setResource('/admin/profile');
            $grid->column('printers.name', __('Printers name'));  //没显示
            $grid->column('project_tags.name', __('Printers comment'));
            $grid->column('note');
            
            return $grid;
        });
        
        $show->field('created_at');
        $show->field('updated_at');
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Project_Tag(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('note');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}