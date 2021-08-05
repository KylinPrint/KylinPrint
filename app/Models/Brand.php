<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Print_;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'manufactors_id'
    ];

    public function manufactors()
    {
        return $this->belongsTo(Manufactor::class);
    }

    public function printers()
    {
        return $this->hasMany(Printer::class);
    }
}
