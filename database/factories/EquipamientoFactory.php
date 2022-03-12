<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Admin\Proyecto\EPC\Equipamiento;

$factory->define(Equipamiento::class, function (Faker $faker) {
    return [
        'item' => $faker->name,
        'type' => $faker->randomElement([
            'equipo_biomedico',
            'equipo_informatico',
            'equipo_mobiliario'
        ]),
        'cost'=>$faker->randomDigit(7),
    ];
});
