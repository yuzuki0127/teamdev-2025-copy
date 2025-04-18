<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TeamSeeder::class,
            UserSeeder::class,
            SurveyTemplateSeeder::class,
            SurveySeeder::class,
            PlanningSeeder::class,
            ActionSeeder::class,
        ]);
    }
}
