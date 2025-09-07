<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superAdmin@gmail.com',
            'password' => bcrypt('superadmin@123'),
            'role_id' => 1,
        ]);

        DB::table('genders')->insert([
            ['name' => 'Male', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Female', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        $this->call(ProvinceSeeder::class);
        
    }
    
}
