<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['date', 'title', 'worker_id', 'code', 'store_id', 'user_id'];

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
