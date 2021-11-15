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
        'adapter',
        'checked',
        'created_at',
        'updated_at'
    ];

    public function printers()
    {
        return $this->belongsTo(Printer::class);
    }

    public function solutions()
    {
        return $this->belongsTo(Solution::class);
    }

    public function adapters()
    {

        return $this->hasMany(Adapter::class, 'adapters_id');
    }

    //拿adapter转成数组
    public function getAdapterAttribute($adapter)
    {
        if (is_string($adapter)) {
            $adapter = explode(',', $adapter);
        }

        return $adapter;
    } 

    //存adapter转成字符串
    public function setAdapterAttribute($adapter) {
        if (is_array($adapter)){
            $this->attributes['adapter'] = trim(implode(',',$adapter), ',');
        }
        //TODO 需添加字符串格式判断
        else $this->attributes['adapter'] = $adapter;
    } 
}
