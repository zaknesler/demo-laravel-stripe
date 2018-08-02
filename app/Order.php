<?php

namespace App;

use App\{ Order, OrderProduct };
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'charge_id',
        'charge_amount',
        'card_last_four',
        'charged_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'charged_at',
    ];

    public function addProduct($product, $quantity)
    {
        $this->products()->attach($product, ['quantity' => $quantity]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->using(OrderProduct::class)
            ->withPivot('quantity');
    }
}
