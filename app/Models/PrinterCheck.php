<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class PrinterCheck extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'printer_checks';
    
    protected $fillable = [
        'title',
        'path',
    ];
}
