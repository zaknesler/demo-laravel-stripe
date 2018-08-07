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
        'user_id',
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

    /**
     * Create an order based on the attributes of a charge.
     *
     * @param  \Stripe\Charge $charge
     * @param  App\User $user
     * @return App\Order
     */
    public static function createForCharge(\Stripe\Charge $charge, $user)
    {
        return self::create([
            'user_id' => $user->id,
            'charge_id' => $charge['id'],
            'charged_at' => $charge['created'],
            'charge_amount' => $charge['amount'],
            'card_last_four' => $charge['source']['last4'],
        ]);
    }

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
