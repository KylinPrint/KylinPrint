<?php

namespace App\Admin\Controllers;

use App\Models\File;
use App\Models\Solution;
use App\Admin\Actions\FileDownload;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Traits\ModelTree;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\FormStep\Form as StepForm;
use Dcat\Admin\Widgets\Alert;

class FileController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */

    // use ModelTree;

    // protected $table = 'files';

    // protected $title = 'File';

    // public function getOrderColumn()
    // {
    //     return null;
    // }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(File::with(['solutions']));

        $grid->column('id', __('ID'))->hide();
        $grid->column('solutions.name', __('Solution'));
        $grid->column('path', __('Path'));


        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();

            $rowArray = $actions->row->toArray();
            if($rowArray['parent_id']){$actions->append(new FileDownload());}
            
        });
        

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
        $show = new Show(File::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('path', __('Path'));
        $show->field('solution_id', __('Solution id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new File());

        
        $form->multipleSteps()
            ->remember()
            ->width('950px')
            ->add('基本信息', function (\Dcat\Admin\FormStep\Form $step) {

                $step->select('parent_id','父目录')
                    ->options(File::where('parent_id','0')
                    ->pluck('path','id'))
                    ->required();
                // h5 表单验证
                $step->select('solutions_id',__('Solution name'))
                    ->options(Solution::all()
                    ->pluck('name', 'id'))
                    ->required();

            })->add('文件上传', function (\Dcat\Admin\FormStep\Form $step) {
                $a = session()->all();
                $ppath = '';
                foreach($a as $value){
                    if(is_array($value)){
                        if(array_key_first($value)=='parent_id'){
                            $ppath = File::where('id',$value['parent_id'])->pluck('path')->first();
                        } 
                    }
                }   
                $step->file('path')->disk('admin')->move($ppath);
            })->done(function () use ($form){
                return view();
            });


        // $form->file('path')->disk('admin');
        // $form->select('parent_id','父目录')->options(File::where('parent_id','0')->pluck('path','id'));

        // $pid = request()->input('parent_id');

        // $ppath = File::where('id',$pid)->pluck('path')->first();

        // $form->file('path')->disk('admin')->move($ppath);
        // //怎么实现在form post时上传文件？¿？¿

        // $form->select('solutions_id',__('Solution name'))->options(Solution::all()->pluck('name', 'id'));

        return $form;
    }
}
