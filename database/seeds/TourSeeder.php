<?php

use App\Tour;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tour::create([
            'package_id'   => 1,
            'name'          => 'Wisata Papuma',
            'price'         => '15000',
            'description'   => 'Paket wisata papuma murah fasilitas lengkap mulai 15000'
        ]);
    }
}
