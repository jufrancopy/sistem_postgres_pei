<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Admin\Proyecto\EPC\TalentoHumano;

$factory->define(TalentoHumano::class, function (Faker $faker) {
    return [
        'item' => $faker->name,
        'hours'=>$faker->DateTime('2008-04-25 08:37:17', 'UTC'),
        'type' => $faker->randomElement([
            'auxilliar_area_administrativa',
            'auxiliar_area_salud',
            'auxiliar_apoyo_salud',
            'directivo',
            'profesional_area_administraiva',
            'profesional_area_salud',
            'tecnico_area_administraiva',
            'tecnico_area_salud'
        ]),
        'cost'=>$faker->randomDigit(7),
    ];
});
