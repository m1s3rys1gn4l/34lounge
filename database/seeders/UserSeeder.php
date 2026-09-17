<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'm1s3rys1gn4l@gmail.com'],
            [
                'name' => 'Admin',
                'password' => '94uFeUsyYHTSZw',
                'email_verified_at' => now(),
            ]
        );
    }
}
