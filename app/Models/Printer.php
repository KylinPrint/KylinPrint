<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'brands_id',
        'model',
        'type',
        'release_date',
        'onsale',
        'network',
        'duplex',
        'pagesize'
    ];

    public function brands()
    {
        return $this->belongsTo(Brand::class);
    }

    public function binds()
    {
        return $this->hasOne(Bind::class);
    }
}
