<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate to start from scratch
        Color::truncate();

        $faker = \Faker\Factory::create();

        $no_fake_records = 50;

        for ($i = 0; $i < $no_fake_records; $i++){
            Color::create([
                'name' => $faker->colorName(),
                'color' => substr($faker->hex_color(), 1),
                'pantone_value' => $faker->numberBetween(10, 15)."-".
                    sprintf("%04d", $faker->numberBetween(0,9999)),
                'year' => $faker->year(),
            ]);
        }

    }
}
