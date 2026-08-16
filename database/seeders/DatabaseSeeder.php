<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@rentalmobil.test',
            'password' => Hash::make('password'),
            'role' => UserRole::SuperAdmin,
        ]);

        // Staff user
        User::factory()->create([
            'name' => 'Staff Operasional',
            'email' => 'staff@rentalmobil.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Staff,
        ]);

        // Categories & Vehicles
        $categories = [
            ['name' => 'City Car', 'slug' => 'city-car'],
            ['name' => 'MPV', 'slug' => 'mpv'],
            ['name' => 'SUV', 'slug' => 'suv'],
        ];

        foreach ($categories as $cat) {
            $category = VehicleCategory::create($cat);
            Vehicle::factory(3)->create(['category_id' => $category->id]);
        }

        // Drivers
        Driver::factory(5)->create();

        // Customers
        Customer::factory(10)->create();
    }
}
