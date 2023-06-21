<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $fillable = ['title', 'code', 'role_id', 'part_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

}
