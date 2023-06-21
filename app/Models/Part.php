<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable= ['title', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Part::class);
    }
}
