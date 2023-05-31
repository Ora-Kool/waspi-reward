<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('badges')->insert([
            [
                "name" => "Beginner",
            ],
            [
                "name" => "Top-fan",
            ],
            [
                "name" => "Super-fan",
            ]
        ]);
    }
}