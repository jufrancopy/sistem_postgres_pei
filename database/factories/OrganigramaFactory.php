<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Admin\Organigrama;
use Faker\Generator as Faker;

$factory->define(Organigrama::class, function (Faker $faker) {
    $niveles = ['estrategico', 'operativo'];
    return [
        'nombre' => $faker->sentence(10),
        'responsable' => $faker->name,
        'nivel' => $niveles[rand(0,1)]
    ];
});
