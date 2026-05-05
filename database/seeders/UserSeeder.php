<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'email' => 'admin@ceff.ch',
            'password' => Hash::make('Pa$$w0rd'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Diogo',
            'surname' => 'Soares',
            'email' => 'Diogo@ceff.ch',
            'password' => Hash::make('Pa$$w0rd'),
            'role' => 'utilisateur',
        ]);
    }
}
