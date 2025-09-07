<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'SuperAdmin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin','created_at' => now(), 'updated_at' => now()],
            // You can add more roles here later, e.g., 'receptionist', 'user'
        ]);
    }
}
