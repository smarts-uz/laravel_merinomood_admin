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

$factory->define(Arniro\Admin\Tests\Fixtures\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$FcUFRDLK9gZ33EINEigU1.XV7BNXJ/T1jO2kqVcY1/SIbTzLrwFKS',
        'remember_token' => str_random(10),
    ];
});
