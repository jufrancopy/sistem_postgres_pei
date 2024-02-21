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

        for ($i = 0; $i < 10; $i++) {
            DB::table('patrimonies')->insert([
                'type' => $faker->word,
                'quantityAccount' => $faker->randomNumber(),
                'detailLocation' => $faker->word,
                'estateQuantity' => $faker->randomNumber(),
                'department' => $faker->word,
                'city' => $faker->city,
                'locality' => $faker->word,
                'description' => $faker->sentence,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'location' => $faker->address,
                'estateNumber' => $faker->word,
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
                'builtAreaM2' => $faker->randomNumber(),
                'builtValueGs' => $faker->randomNumber(),
                'propertyValueGs' => $faker->randomNumber(),
                'totalValueGs' => $faker->randomNumber(),
                'possessionRentWithoutTitle' => $faker->word,
            ]);
        }
    }
}
