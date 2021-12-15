<?php

namespace App\Admin\Controllers;

use App\Models\Bind;
use App\Models\Solution;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;


class BindController extends AdminController
{
    
    protected $title = '详情';
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Bind(), function (Grid $grid) {
            
            
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
        return Show::make($id, Bind::with(['printers','solutions']), function (Show $show) {
            $show->panel()->tools(function ($tools) 
            {
                $tools->disableList();
                $tools->disableEdit();
                $tools->disableDelete();
            });
            
            $show->field('printers.model', __('打印机型号'));
            $show->field('solutions.name', __('解决方案名'));
            $show->field('adapter', __('适配平台'))->as(function($adapter){
                $Arr = array();
                foreach($adapter as $value){$Arr[] = $value;}
                return $Arr;
            })->label();;
            
            $show->field('checked', __('适配状态'))->using([1 => '已适配',2 => '待验证',0 => '未适配']);
            $show->field('solutions.name', __('解决方案名'));
            $show->auth('互认证状态')->using([1 => '是',2 => '否']);
            $show->field('completion_time','适配完成时间');
            //$show->field('solutions.comment', __('解决方案备注'));
            $show->field('comments', __('本型号注意事项'));
            $show->detail('解决方案')->unescape()->as(function($detail){ 
                $detail = Solution::where('id',$this->solutions_id)->pluck('detail')->first();
                return $detail;
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Bind(), function (Form $form) {
            
        });
    }
}
