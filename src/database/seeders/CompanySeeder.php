<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use Faker\Factory as Faker;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
