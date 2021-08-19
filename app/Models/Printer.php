<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function solutions()
    {
        return $this->belongsToMany(Solution::class, 'binds', 'printers_id', 'solutions_id');
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
        return $this->hasMany(Industry_Tag_Bind::class, 'printer_id');
    }

    public function principle_tags()
    {
        return $this->belongsTo(Principle_Tag::class);
    }
}
