<?php

namespace App;

use App\{ Order, OrderProduct };
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(OrderProduct::class);
    }
}
