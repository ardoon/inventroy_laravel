<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = ['title'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

}
