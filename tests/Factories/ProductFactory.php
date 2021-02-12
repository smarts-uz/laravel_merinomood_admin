<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Arniro\Admin\Tests\Fixtures\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->numberBetween(500, 5000),
        'user_id' => $faker->randomElement(\Arniro\Admin\Tests\Fixtures\User::pluck('id'))
    ];
});
