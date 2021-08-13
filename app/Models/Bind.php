<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bind extends Model
{
    use HasFactory;

    protected $table = 'binds';

    protected $fillable = [
        'printers_id',
        'solutions_id',
        'checked'
    ];

    public function printers()
    {
        return $this->belongsToMany(Printer::class);
    }

    public function solutions()
    {
        return $this->belongsTo(Solution::class);
    }
}
