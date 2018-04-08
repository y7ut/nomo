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

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Board::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'url' => $faker->name,
        'banner' => $faker->imageUrl(),

    ];
});
$factory->define(App\Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
$factory->define(App\Post::class, function (Faker $faker) {
    $user_ids = \App\User::all()->pluck("id")->toArray();
    $board_ids = \App\Board::all()->pluck("id")->toArray();
    return [
        'title' => $faker->name,
        'content' => $faker->sentence,
        'url' => $faker->name,
        'user_id' => $faker->randomElement($user_ids),
        'board_id' => $faker->randomElement($board_ids),
    ];
});
$factory->define(App\Comment::class, function (Faker $faker) {
    $user_ids = \App\User::all()->pluck("id")->toArray();
    $post_ids = \App\Post::all()->pluck("id")->toArray();
    return [
        'content' => $faker->sentence,
        'user_id' => $faker->randomElement($user_ids),
        'post_id' => $faker->randomElement($post_ids),
    ];
});

