<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // DB::table('users')->truncate();

        User::factory()
            ->count(5)
            ->create();
    }
}
