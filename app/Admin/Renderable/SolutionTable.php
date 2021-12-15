<?php

namespace App\Admin\Renderable;


use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;
use App\Models\Bind;

class SolutionTable extends LazyRenderable
{

    public function grid(): Grid
    {
        return Grid::make(Bind::with(['printers','solutions']), function (Grid $grid) {
            
            $grid->setActionClass(Grid\Displayers\Actions::class);

            $grid->model()->where([
                ['printers_id', $this->id],
                ['adapter','like','%'.$this->adapter.'%']
            ]);

            $grid->column('solutions.name', __('解决方案名'));
            $grid->checked(__('适配状态'))->display(function ($checked) {
                switch($checked){
                    case '0':
                        return '未适配';
                        break;
                    case '1':
                        return '已验证';
                        break;
                    case '2':
                        return '待验证';
                        break;
                    case '3':
                        return '已适配';
                        break;
                    default:
                        return '有待验证';
                }

            });
            $grid->column('adapter',__('适配平台'))->center()->badge('default')->width('80px');
            $grid->column('auth','互认证状态')->display(function($auth){
                switch ($auth){
                    case 1:
                        return '<i class="fa fa-check text-green"></i>';
                        break;
                    case 2:
                        return '<i class="fa fa-close text-red"  ></i>';
                        break;
                    default:
                        return '<i class="fa fa-close text-red"  ></i>';
                }
            });
            $grid->column('solutions.detail',__('适配方案'))->limit(500)->center();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();     
                $actions->disableView();
                $curStr = '<a href = "/admin/binds/'.$actions->row['id'].'">详情</a>';
                $actions->append($curStr);

            });

            $grid->paginate(5);
            $grid->disableRefreshButton();
            $grid->disableCreateButton();

        });
    }
}
