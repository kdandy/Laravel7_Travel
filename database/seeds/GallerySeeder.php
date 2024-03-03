<?php

use App\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gallery::create([
            'company_id'    => 1,
            'image'         => 'public/gallery/slide1.png'
        ]);

        Gallery::create([
            'company_id'    => 1,
            'image'         => 'public/gallery/slide2.png'
        ]);

        Gallery::create([
            'company_id'    => 1,
            'image'         => 'public/gallery/slide3.png'
        ]);

        Gallery::create([
            'tour_id'    => 1,
            'image'         => 'public/gallery/slide-1.png'
        ]);

        Gallery::create([
            'tour_id'    => 1,
            'image'         => 'public/gallery/slide-2.png'
        ]);

        Gallery::create([
            'tour_id'    => 1,
            'image'         => 'public/gallery/slide-3.png'
        ]);
    }
}
