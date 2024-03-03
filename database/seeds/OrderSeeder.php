<?php

use App\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create([
            'user_id'           => 2,
            'tour_id'           => 1,
            'transaction_id'    => 851420,
            'via'               => 'qris',
            'channel'           => 'linkaja',
            'amount'             => '30000',
            'payment_no'        => '00020101021126690017COM.TELKOMSEL.WWW011893600911002144300102152003110414430010303UME51480015ID.OR.GPNQR.WWW02180000000000000000000303UME520411115802ID5917iPaymu - PT. DEMO6008Denpasar6105801136210010666050353033606304577C',
            'expired'           => date('Y-m-d H:i:s', strtotime('+1 day')),
            'status'            => 'Pending',
            'hometown'          => 'Bondowoso',
            'qty'               => 2,
            'date_start'        => date('Y-m-d H:i:s', strtotime('+1 day')),
            'date_end'          => date('Y-m-d H:i:s', strtotime('+2 day')),
            'rating'            => 5,
            'testimonial'       => 'Perjalanan nyaman wisatanya indah'
        ]);
    }
}
