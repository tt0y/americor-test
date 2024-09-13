<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Company;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $companies = Company::all();

        // Для каждой компании создаем 5 случайных сотрудников
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
