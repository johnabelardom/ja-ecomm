<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        if (User::where('email', 'admin@user.com')->count() == 0) {
            User::insert([
                [
                    'name' => 'Admin User',
                    'email' => 'admin@user.com',
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'password' => Hash::make('password'),
                    // 'current_team_id' => '',
                    // 'profile_photo_path' => '',
                    'role' => 'admin',
                ],
                [
                    'name' => 'Seller User',
                    'email' => 'seller@user.com',
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'password' => Hash::make('password'),
                    // 'current_team_id' => '',
                    // 'profile_photo_path' => '',
                    'role' => 'seller',
                ],
                [
                    'name' => 'Buyer User',
                    'email' => 'buyer@user.com',
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'password' => Hash::make('password'),
                    // 'current_team_id' => '',
                    // 'profile_photo_path' => '',
                    'role' => 'buyer',
                ],
                [
                    'name' => 'Carrier User 1',
                    'email' => 'carrier1@user.com',
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'password' => Hash::make('password'),
                    // 'current_team_id' => '',
                    // 'profile_photo_path' => '',
                    'role' => 'carrier1',
                ],
            ]);
        }

        Product::factory()
            ->count(50)
            ->create();
    }
}
