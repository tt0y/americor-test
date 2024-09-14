<?php

namespace Database\Seeders;

use App\Models\Company;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Creating 2 companies
        for ($i = 0; $i < 2; $i++) {
            Company::create([
                'name' => $faker->company,
                'email' => $faker->companyEmail,
                'website' => $faker->url,
            ]);
        }
    }
}
