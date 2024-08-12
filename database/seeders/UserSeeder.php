<?php

namespace Database\Seeders;

use App\Repositories\UserRepository;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(UserRepository::class)->createUser([
            'first_name' => 'Liam',
            'last_name' => 'Johnson',
            'email' => 'liam@gmail.com',
            'password' => 'Password1234',
            'email_verified_at' => now(),
        ]);
    }
}
