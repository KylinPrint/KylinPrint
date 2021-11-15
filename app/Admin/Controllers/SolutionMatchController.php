<?php

namespace App\Admin\Controllers;

use App\Models\SolutionMatch;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Admin\Actions\FileDownload;

class SolutionMatchController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new SolutionMatch(), function (Grid $grid) {
            
            $grid->disableCreateButton();
            
    
            
                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->disableDelete();
                    $actions->disableEdit();
                    $actions->disableQuickEdit();
                });
            
    
            $grid->column('id', __('ID'))->hide();
            $grid->column('title', __('Title'));
            $grid->column('path', __('Path'));
    
    
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
    
                $rowArray = $actions->row->toArray();
                
                    $actions->append(new FileDownload());
                
                
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
        return Show::make($id, new SolutionMatch(), function (Show $show) {
            
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new SolutionMatch(), function (Form $form) {
            
        });
    }
}
