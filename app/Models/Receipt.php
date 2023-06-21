<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = ['date', 'worker_id', 'code', 'part_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function outputs()
    {
        return $this->hasMany(Output::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
    
    public function part()
    {
        return $this->belongsTo(Part::class);
    }

}
