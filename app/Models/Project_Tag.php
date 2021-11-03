<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project_Tag extends Model
{
    use HasFactory;

    protected $table = 'project_tags';

    protected $fillable = [
        'name'
    ];

    public function printers():BelongsToMany
    {
        $pivotTable = 'project_tag_binds'; // 中间表

        $relatedModel = Printer::class; // 关联模型类名

        return $this->belongsToMany($relatedModel, $pivotTable, 'printers_id', 'project_tags_id');
        // return $this->belongsToMany(Printer::class, 'project_tag_binds', 'printers_id', 'project_tags_id');
    }

    public function project_tag_binds()
    {
        return $this->hasMany(Project_Tag_Bind::class, 'project_tags_id');
    }
}
