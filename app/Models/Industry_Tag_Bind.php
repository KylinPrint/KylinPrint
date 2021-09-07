<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry_Tag_Bind extends Model
{
    use HasFactory;

    protected $table = 'industry_tag_binds';

    protected $fillable = [
        'printers_id',
        'industry_tags_id',
    ];
}
