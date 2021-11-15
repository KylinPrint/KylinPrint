<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry_Tag_Bind extends Model
{
	use HasFactory;    

	protected $table = 'industry_tag_binds';

	public $timestamps = TRUE;

    protected $fillable = [
        'printers_id',
        'industry_tags_id',
        'created_at',
        'updated_at'
    ];

    public function printers()
    {
        return $this->belongsTo(Printer::class);
    }

    public function industry_tags()
    {
        return $this->belongsTo(Industry_Tag::class);
    }

}
