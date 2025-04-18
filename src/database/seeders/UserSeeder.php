<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'team_id' => 1,
                'name' => '田中太郎',
                'email' => 'tanaka@example.com',
                'password' => Hash::make('Password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'team_id' => 2,
                'name' => '宮坂 朱音',
                'email' => 'akane@example.com',
                'password' => Hash::make('Password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
