<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;

class PatrimonySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $departmentsParaguay = [
            'Concepcion', 'San Pedro', 'Cordillera', 'Guaira', 'Caaguaza',
            'Caazapa', 'Itapua', 'Misiones', 'Paraguari', 'Alto Parana',
            'Central', 'Ñeembucu', 'Amambay', 'Canindeyu', 'Presidente Hayes',
            'Boqueron', 'Alto Paraguay', 'Asuncion',
        ];

        for ($i = 0; $i < 10; $i++) {
            DB::table('patrimonies')->insert([
                'type' => $faker->randomElement(['BIEN DE USO', 'BIEN DE RENTA']),
                'quantityAccount' => $faker->randomNumber(),
                'detailLocation' => $faker->word,
                'estateQuantity' => $faker->randomNumber(),
                'department' => strtoupper($faker->randomElement($departmentsParaguay)),
                'city' => $faker->city,
                'locality' => $faker->word,
                'description' => $faker->sentence,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'location' => $faker->address,
                'infrastructureType' => $faker->word,
                'startDateContract' => $faker->date,
                'endDateContract' => $faker->date,
                'estateNumber' => $faker->randomNumber(),
                'registryNumber' => $faker->word,
                'cadastreCurrentAccount' => $faker->randomNumber(),
                'estateStatus' => $faker->randomNumber(),
                'committedInvestment' => $faker->randomNumber(),
                'transfer' => $faker->randomNumber(),
                'balanceForTransfer' => $faker->randomNumber(),
                'tenant' => $faker->name,
                'rentAmount' => $faker->randomNumber(),
                'rentAmountPeriod' => $faker->randomNumber(),
                'contractResolution' => $faker->word,
                'currentPeriodEnd' => $faker->word,
                'statusDocumentation' => $faker->word,
                'landAreaMt2' => $faker->randomNumber(),
                'landAreaHectares' => $faker->randomNumber(),
                'landSubArea' => $faker->word,
                'builtAreaM2' => $faker->randomFloat(2, 50, 200), // 2 es la cantidad de decimales, 50 es el valor mínimo y 200 es el valor máximo
                'builtValueGs' => $faker->randomFloat(2, 1000000, 5000000), // Ajusta los valores mínimo y máximo según tus necesidades
                'propertyValueGs' => $faker->randomNumber(),
                'totalValueGs' => $faker->randomNumber(),
                'possessionRentWithoutTitle' => $faker->word,
            ]);
        }
    }
}
