<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class JumpInfo extends RowAction
{
    public $name = '详情';

    public function handle(Model $model)
    {
        // $model ...

        return $this->response()->success('Success message.')->refresh();
    }
    
    public function href(){
        return "/admin/solutions/".$this->row->solutions_id;
        
    }

}