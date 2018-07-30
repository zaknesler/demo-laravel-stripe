<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => 'Awesome Product',
        'description' => $faker->paragraph,
        'price' => $faker->numberBetween(1000, 50000),
        'image' => 'https://via.placeholder.com/250',
        'stock' => $faker->randomNumber(2),
    ];
});
