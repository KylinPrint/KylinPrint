<?php

namespace App\Admin\Renderable;

use App\Models\Project_Tag_Bind;
use Dcat\Admin\Grid;
use App\Models\Project_Tag as Project_Tad_Model;
use App\Models\Printer;
use Dcat\Admin\Support\LazyRenderable;
use Dcat\Admin\Widgets\Table;

class ProjectTable extends LazyRenderable
{
    public function render()
    {
        // 获取ID
        $id = $this->key;

        // 获取其他自定义参数

        $arrIds = Project_Tag_Bind::where('printers_id',$id)->get('project_tags_id')->toArray();

        $data = array();

        foreach ($arrIds as $value){
            $a = Project_Tad_Model::where('id',$value)->get(['id','name','created_at'])->toArray();
            if($data){$data = array_merge($data,$a);}
            else{$data = $a;}   
        }

        $titles = [
            'ID',
            'Project Name',
            'Created At',
        ];

        return Table::make($titles, $data);
    }
}

