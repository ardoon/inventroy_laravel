<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['title','role_id'];

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function parent()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
