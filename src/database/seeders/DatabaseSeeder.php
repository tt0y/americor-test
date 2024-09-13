<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
//    public function run()
//    {
//        \App\Models\User::create([
//            'name' => 'Admin',
//            'email' => 'admin@admin.com',
//            'password' => bcrypt('password'),
//        ]);
//    }
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
