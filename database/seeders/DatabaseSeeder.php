<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\InitialDataSeeder;
use Database\Seeders\MaterialRequestSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\InspectionEnvironmentTemplateSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EmployeeSeeder::class,
            InspectionEnvironmentTemplateSeeder::class,
        ]);
    }
}
