<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Teacher::factory()->create([
            'name' => 'Teacher',
            'email' => 'teacher@assessment.com',
            'subject' => 'English',
            'password' => bcrypt('password')
        ]);

        //\App\Models\Teacher::factory(2)->create();
        \App\Models\ClassSection::factory(2)->create();
        \App\Models\Student::factory(30)->create();
        //\App\Models\Assessment::factory(10)->create();
    }
}
