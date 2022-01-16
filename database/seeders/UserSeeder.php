<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'مهدی آقامحمدی',
            'email' => 'mahdi@gmail.com',
            'password' => Hash::make(12345678),
            'user_type' => 1,
            'api_token' => Str::random(100),
        ]);
    }
}
