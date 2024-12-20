<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Room;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Organization::create([
            'name' => 'Engineering & TI'
        ]);
        Organization::create([
            'name' => 'Human Capital'
        ]);
        Organization::create([
            'name' => 'Divisi Sipil Umum'
        ]);
        Organization::create([
            'name' => 'Perencanaan Jalan Tol'
        ]);

        Room::create([
            'name' => 'Sutami',
            'floor' => '6',
            'capacity' => 30
        ]);
        Room::create([
            'name' => 'Inovasi',
            'floor' => '8',
            'capacity' => 11
        ]);
        Room::create([
            'name' => 'Kapital',
            'floor' => '8',
            'capacity' => 18
        ]);

        User::create([
            'name' => 'Administrator',
            'email' => 'adminrapat@hutamakarya.com',
            'password' => Hash::make('Hutama123!'),
            'role' => 'superadmin'
        ]);
    }
}
