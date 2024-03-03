<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(TourSeeder::class);
        $this->call(GallerySeeder::class);
        $this->call(OrderSeeder::class);
    }
}
