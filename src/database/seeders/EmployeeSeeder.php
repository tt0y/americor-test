<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $companies = Company::all();

        // For each company, we create 5 random employees
        foreach ($companies as $company) {
            for ($i = 0; $i < 5; $i++) {
                Employee::create([
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'company_id' => $company->id,
                    'email' => $faker->email,
                    'phone' => $faker->phoneNumber,
                ]);
            }
        }
    }
}
