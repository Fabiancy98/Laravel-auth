<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@moses.com',
            'username' => 'Super Admin',
            'sex' => 'Male',
            'role' => 'SuperAdmin',
            'password' => \Hash::make('1admin@u!'),
            'verified' => Carbon::now(),
        ]);
    }
}
