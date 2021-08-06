<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;

    protected $table = 'solutions';

    protected $fillable = [
        'name',
        'comment',
        'source'
    ];

    public function printers()
    {
        return $this->belongsToMany(Printer::class, 'binds', 'solutions_id', 'printers_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
