<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'unit_id', 'stock', 'unit_sub_id', 'proportion'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function subunit()
    {
        return $this->belongsTo(Unit::class, 'unit_sub_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}
