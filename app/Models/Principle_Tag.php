<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Principle_Tag extends Model
{
    use HasFactory;

    protected $table = 'principle_tags';

    protected $fillable = [
        'name'
    ];


}
