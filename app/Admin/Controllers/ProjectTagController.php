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
            $grid->column('printers.model', __('Printers name'));  
            $grid->column('project_tags.name', __('Project name'));
            $grid->column('note');

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();     
                $actions->disableView();
                $actions->append('<a href=""><i class="fa fa-eye"></i></a>');
            });
            
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
        return Form::make(Project_Tag::with(['printers','project_tag_binds']), function (Form $form){
            
            $form->display('id');
            $form->text('name');

            $form->hasMany('project_tag_binds', '涉及打印机', function (Form\NestedForm $form){

                $form->text('printer_model', '打印机名')->disable()->customFormat(function ($v) {
                    $a = $this->toArray();
                    $b = Printer::where('id',$a['printers_id'])->pluck('model')->first();
                    return $b;
                });

                $form->text('note');
                        
            })->disableDelete()->disableCreate()->useTable();

            $form->confirm('确定更新吗？', 'edit');
            $form->confirm('确定创建吗？', 'create');
            $form->confirm('确定提交吗？');
        });
    }
}