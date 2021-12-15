<?php

namespace App\Admin\Controllers;

use App\Models\PrinterCheck;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Admin\Actions\PrinterCheckDownload;
use App\Admin\Actions\Modal\PrinterCheckModal;
use Illuminate\Support\Facades\Storage;

class PrinterCheckController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new PrinterCheck(), function (Grid $grid) {

            $grid->disableCreateButton();
            
            $grid->tools(function (Grid\Tools $tools) { 
                //Solution匹配
                $tools->append(new PrinterCheckModal());
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();
                $actions->disableQuickEdit();
    
                $rowArray = $actions->row->toArray();

                $actions->append(new PrinterCheckDownload());
                          
            });
            
            $grid->column('id', __('ID'))->hide();
            $grid->column('title', __('Title'));
            $grid->column('path', __('Path'));
    
            
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
        return Show::make($id, new PrinterCheck(), function (Show $show) {
            
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new PrinterCheck(), function (Form $form) {
            $form->deleting(function (Form $form){
                $data = $form->model()->toArray();
                foreach($data as $value){
                    $disk = Storage::disk('admin');
                    $disk -> delete(''.$value['path']);
                }
            });
        });
    }
}
