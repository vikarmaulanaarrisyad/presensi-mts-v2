<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswas = [];
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 10; $i++) {
            $siswas[] = [
                'name' => $faker->name,
                'nis' => '1010' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'password' => bcrypt('password123'),
                'kelas' => $faker->randomElement(['VII-A', 'VII-B', 'VIII-A', 'IX-A']),
                'role' => 'murid',
                'no_wa' => '0812' . $faker->randomNumber(8, true),
                'fingerprint_id' => null, // Biarkan null agar bisa dites fitur rekam jari
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('siswas')->insert($siswas);
    }
}
