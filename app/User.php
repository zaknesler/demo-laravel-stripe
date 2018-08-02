<?php

namespace App;

use App\Order;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'stripe_customer_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function createStripeCustomer($source)
    {
        // if ($this->stripe_customer_id) {
        //     return $this->getStripeCustomer();
        // }

        $customer = collect(\Stripe\Customer::create([
            'email' => $this->email,
            'source' => $source,
        ]));

        $this->update(['stripe_customer_id' => $customer['id']]);

        return $customer;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
