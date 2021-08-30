<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'brands_id',
        'model',
        'type',
        'release_date',
        'onsale',
        'network',
        'duplex',
        'pagesize'
    ];

    public function brands()
    {
        return $this->belongsTo(Brand::class);
    }

    public function solutions():BelongsToMany
    {
        $pivotTable = 'binds'; // 中间表

        $relatedModel = Solution::class; // 关联模型类名

        return $this->belongsToMany($relatedModel, $pivotTable, 'printers_id', 'solutions_id');

    }

    public function binds()
    {
        return $this->hasMany(Bind::class, 'printers_id');
    }
    public function industry_tags()
    {
        return $this->belongsToMany(Industry_Tag::class,'industry_tag_binds', 'printers_id', 'industry_tags_id');
    }

    public function industry_tag_binds()
    {
        return $this->hasMany(Industry_Tag_Bind::class, 'printers_id');
    }

    public function principle_tags()
    {
        return $this->belongsTo(Principle_Tag::class);
    }

    public function project_tags()
    {
        return $this->belongsToMany(Project_Tag::class,'project_tag_binds', 'printers_id', 'project_tags_id');
    }

    public function adapters()
    {
        return $this->hasManyThrough(Adapter::class,Bind::class,'printers_id','binds_id','id','id');
    }
}
