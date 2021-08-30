<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_Tag extends Model
{
    use HasFactory;

    protected $table = 'project_tags';

    protected $fillable = [
        'name'
    ];

    public function printers()
    {
        return $this->belongsToMany(Printer::class, 'project_tag_binds', 'printers_id', 'project_tags_id');
    }
}
