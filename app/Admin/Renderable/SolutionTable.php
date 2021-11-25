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

            $grid->model()->where('printers_id', $this->id);

            $grid->column('solutions.name', __('Solution name'));
            $grid->column('solutions.comment', __('Solution comment'));
            $grid->checked(__('Checked'))->display(function ($checked) {
                if     ($checked == '0') { return '未验证'; }
                elseif ($checked == '1') { return '已验证'; }
                else                     { return ''; };
            });
            $grid->column('adapter',__('适配平台'))->label();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();     
                $actions->disableView();
                $curStr = '<a href = "/admin/solutions/'.$actions->row['solutions_id'].'">详情</a>';
                $actions->append($curStr);

            });

            $grid->paginate(10);
            $grid->disableRefreshButton();
            $grid->disableCreateButton();

        });
    }
}
