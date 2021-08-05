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

    public function binds()
    {
        return $this->hasMany(Bind::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
