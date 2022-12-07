<?php

namespace Database\Seeders;

use App\Models\Signature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SignatureSeeder extends Seeder
{
    public function run()
    {
        DB::table('signatures')->truncate();

        Signature::factory()
            ->count(50)
            ->create();
    }
}
