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

$factory->define(Arniro\Admin\Tests\Fixtures\Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        'commentable_type' => \Arniro\Admin\Tests\Fixtures\Post::class,
        'commentable_id' => \Arniro\Admin\Tests\Fixtures\Post::inRandomOrder()->first()->id
    ];
});
