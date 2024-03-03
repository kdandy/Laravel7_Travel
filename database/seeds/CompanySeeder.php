<?php

use App\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'id'            => 1,
            'logo'          => 'public/logo/favicon.png',
            'name'          => 'MTravel',
            'email'         => 'admin@mtravel.lavinza.me',
            'description'   => 'MTravel merupakan perusahaan yang berjalan dibidang transportasi dan pariwisata dengan layanan paket wisata yang paling murah dibandingkan toko sebelah',
            'address'       => 'Jl. Arjasa No.1, Jember',
            'phone'         => '081234567890',
            'whatsapp'      => '081234567890',
            'latitude'      => '-8.124862',
            'longitude'     => '113.744948',
            'testimonial'   => 'Pelanggan kami mencintai kita! Baca apa yang mereka katakan di bawah ini.',
            'va'            => '1179002331571857',
            'api_key'       => 'EDE93F83-C9F4-474A-95A4-C2FA2EB7EB81'
        ]);
    }
}
