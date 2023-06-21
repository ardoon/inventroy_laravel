<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    protected $fillable = ['special', 'value', 'date', 'unit_id', 'worker_id', 'description', 'product_id', 'user_id', 'receipt_id', 'stock_of_time'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    
    public function part()
    {
        return $this->belongsTo(Part::class);
    }
    
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
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
