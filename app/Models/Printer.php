<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $table = 'printers';

    protected $fillable = [
        'brands_id',
        'model',
        'manufactors_id',
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

    public function manufactors()
    {
        return $this->belongsTo(Manufactor::class);
    }

    public function binds()
    {
        return $this->hasOne(Bind::class);
    }
}
