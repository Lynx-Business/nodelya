<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()
            ->firstOrCreate(
                [
                    'is_admin' => true,
                ],
                [
                    'first_name'        => 'super',
                    'last_name'         => 'admin',
                    'email'             => 'super.admin@lynx.com',
                    'email_verified_at' => now(),
                    'password'          => Hash::make('Password-1234'),
                ]);
    }
}
