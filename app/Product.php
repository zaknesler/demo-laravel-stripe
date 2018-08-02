<?php

namespace App;

use App\{ Order, OrderProduct };
use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Product extends Model implements Buyable
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

    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    public function getBuyableDescription($options = null)
    {
        return $this->name;
    }

    public function getBuyablePrice($options = null)
    {
        return $this->price / 100;
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(OrderProduct::class);
    }
}
