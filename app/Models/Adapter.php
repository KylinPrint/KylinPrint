<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adapter extends Model
{
    use HasFactory;

    protected $table = 'adapters';

    protected $fillable = [
        'sys',
        'soc',
        'binds_id'
    ];
    public function binds()
    {
        return $this->belongsTo(Bind::class);
    }
}
