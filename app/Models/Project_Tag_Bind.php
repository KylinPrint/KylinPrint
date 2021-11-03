<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_Tag_Bind extends Model
{
    use HasFactory;

    protected $table = 'project_tag_binds';

    protected $fillable = [
        'printers_id',
        'project_tags_id',
        'note'
    ];

    public function printers()
    {
        return $this->belongsTo(Printer::class);
    }

    public function project_tags()
    {
        return $this->belongsTo(Project_Tag::class);
    }
}
