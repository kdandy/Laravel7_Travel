<?php

namespace App\Http\Controllers;

use App\Company;
use App\Helpers\Ipaymu;
use App\Order;
use App\Rules\Tanggal;
use App\Tour;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $ipaymu;
    public function __construct() {
        $company = Company::find(1);
        $this->ipaymu = new Ipaymu($company->api_key,$company->va);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ipaymu = $this->ipaymu;
        $orders = Order::all();
        foreach ($orders as $order) {
            if ($order->paymentTime == null && $order->expired < now()) {
                $order->status = 'Expired';
                $order->save();
            }

            $req = $this->ipaymu->checkTransaction($order->transaction_id);
            if ($order->paymentTime == null && array_key_exists('WaktuBayar', $req)) {
                if ($req['WaktuBayar'] != "") {
                    $order->paymentTime = date('Y-m-d H:i:s', strtotime($req['WaktuBayar']));
                    $order->status = 'Success';
                    $order->save();
                }
            }
        }
        return view('order.index', compact('ipaymu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Tour $tour, $slug)
    {
        if ($slug != Str::slug($tour->name)) {
            return abort(404);
        }
        return view('order.create', compact('tour'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tour $tour)
    {
        $request->validate([
            'date_start'        => ['required', 'date', 'after:now', new Tanggal($request->date_start, $request->date_end)],
            'date_end'          => ['required', 'date', 'after:date_start', new Tanggal($request->date_start, $request->date_end)],
            'quantity'          => ['required', 'numeric', 'min:1'],
            'paymentMethod'     => ['required', 'string', 'max:4'],
            'paymentChannel'    => ['required', 'string', 'max:8'],
            'hometown'          => ['required', 'string'],
            'note'              => ['nullable', 'string'],
        ]);

        $amount = $tour->price * $request->quantity * Carbon::parse($request->date_end)->diffInDays(Carbon::parse($request->date_start));
        $this->ipaymu->setBuyer([
            'name'      => auth()->user()->name,
            'phone'     => auth()->user()->phone,
            'email'     => auth()->user()->email
        ]);

        $this->ipaymu->setCart([
            'amount'            => $amount,
            'description'       => $request->quantity.' Tiket '.$tour->name,
            'paymentMethod'     => $request->paymentMethod,
            'paymentChannel'    => $request->paymentChannel,
            'referenceId'       => date('YmdHis')
        ]);

        $ipaymu = $this->ipaymu->checkout();
        $order = Order::create([
            'user_id'           => auth()->user()->id,
            'tour_id'           => $tour->id,
            'transaction_id'    => $ipaymu['Data']['TransactionId'],
            'via'               => $request->paymentMethod,
            'channel'           => $request->paymentChannel,
            'amount'            => $amount,
            'payment_no'        => $ipaymu['Data']['PaymentNo'],
            'expired'           => $ipaymu['Data']['Expired'],
            'status'            => 'Pending',
            'qty'               => $request->quantity,
            'hometown'          => $request->hometown,
            'note'              => $request->note,
            'date_start'        => date('Y-m-d H:i:s',strtotime($request->date_start)),
            'date_end'          => date('Y-m-d H:i:s',strtotime($request->date_end)),
        ]);

        return redirect()->route('order.show', ['order' => $order]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if ($order->paymentTime == null && $order->expired < now()) {
            $order->status = 'Expired';
            $order->save();
        }

        if (auth()->user()->role->name == "Admin" || auth()->user()->id == $order->user_id) {
            return view('order.show', compact('order'));
        }

        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->paymentTime = date('Y-m-d H:i:s', strtotime($request->paymentTime));
        $order->status = 'Success';
        $order->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        alert()->success(__('alert.success-delete', ['attribute' => 'Order']), __('Success'));
        return redirect()->route('order.index');
    }

    public function checkTransaction($id)
    {
        return $this->ipaymu->checkTransaction($id);
    }

    public function testimonial(Request $request, Order $order)
    {
        $order->rating = $request->rating;
        $order->testimonial = $request->testimonial;
        $order->save();

        alert()->success(__('alert.success-update',['attribute' => 'Testimonial']), __('Success'));
        return redirect()->back();
    }
}
