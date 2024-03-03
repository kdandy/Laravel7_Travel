<?php

use App\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create(['name' => 'Reguler']);
        Package::create(['name' => 'Paket Khusus']);
    }
}
