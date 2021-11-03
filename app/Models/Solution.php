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
        'source',
        'amd64',
        'arm64',
        'mips64el',
        'loongarch64',
        'detail',
    ];

    public function printers()
    {
        return $this->belongsToMany(Printer::class, 'binds', 'solutions_id', 'printers_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function binds()
    {
        return $this->hasMany(Bind::class, 'solutions_id');
    }
}
