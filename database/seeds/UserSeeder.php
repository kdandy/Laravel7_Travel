<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'              => 'Admin',
            'role_id'           => 1,
            'phone'             => '081234567890',
            'address'           => 'Jl. Indonesia 1',
            'email'             => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('asdf1234'),
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'name'              => 'Member',
            'phone'             => '080987654321',
            'address'           => 'Jl. Indonesia 2',
            'email'             => 'member@gmail.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('asdf1234'),
            'remember_token'    => Str::random(10),
        ]);
    }
}
