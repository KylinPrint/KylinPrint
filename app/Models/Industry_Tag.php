<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry_Tag extends Model
{
    use HasFactory;

    protected $table = 'industry_tags';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    public function printers()
    {
        return $this->belongsToMany(Printer::class, 'industry_tag_binds', 'printers_id', 'industry_tags_id');
    }

    public function industry_tag_binds()
    {
        return $this->hasMany(IndustryTagBind::class, 'industry_tags_id');
    }
}
