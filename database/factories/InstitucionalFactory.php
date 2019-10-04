<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Admin\Institucional;
use Faker\Generator as Faker;

$factory->define(Institucional::class, function (Faker $faker) {
    return [
        'mision' => $faker->sentence(20),
        'vision' => $faker->sentence(20),
    ];
});
