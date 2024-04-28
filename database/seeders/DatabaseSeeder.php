<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
<<<<<<< HEAD
            UserSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
=======
            UserSeeder::class
>>>>>>> 8f263181d1538d00f91cd807ef16c74ed76ffea6
        ]);
    }
}
