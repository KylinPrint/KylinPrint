<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry_Tag extends Model
{
    use HasFactory;

    protected $table = 'industry_tags';

    protected $fillable = [
        'name'
    ];

    public function printers()
    {
        return $this->belongsToMany(Printer::class, 'industry_tag_binds', 'printers_id', 'industry_tags_id');
    }

    public function industry_tag_binds()
    {
        return $this->hasMany(Industry_Tag_Bind::class, 'industry_tags_id');
    }
}
