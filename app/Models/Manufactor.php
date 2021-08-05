<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'isconnected'
    ];

    public function brands()
    {
        return $this->hasMany(Brand::class, 'manufactors_id');
    }

    public function printers()
    {
        return $this->hasMany(Printer::class);
    }
}
