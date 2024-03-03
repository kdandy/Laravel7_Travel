<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Order;
use App\Tour;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function gallery()
    {
        $galleries = Gallery::whereCompanyId(1)->orderBy('id','desc')->get();

        return response()->json([
            'success'   => true,
            'message'   => __('alert.success-get', ['attribute' => 'images']),
            'data'      => $galleries
        ],200);
    }

    public function order($id)
    {
        $user = User::find($id);
        if ($user->role->name == 'Admin') {
            return datatables()->of(Order::with('tour')->get())
                ->addColumn('tour_name', function($data){
                    return '<a href="'.route('tour.show',['tour' => $data->tour , 'slug' => Str::slug($data->tour->name)]).'">'.$data->tour->name.'</a>';
                })
                ->addColumn('nominal', function($data){
                    return 'Rp. '.substr(number_format($data->amount, 2, ',', '.'),0,-3);
                })
                ->addColumn('expired', function($data){
                    return date('d F Y, H:i:s', strtotime($data->expired));
                })
                ->addColumn('action', function($data){
                    return '<a href="'. route('order.show', $data->id) .'" title="'. __('Detail') .'" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>';
                })
                ->rawColumns(['action','tour_name', 'nominal', 'expired'])
                ->make(true);
        } else {
            return datatables()->of(Order::with('tour')->where('user_id', $id)->get())
                ->addColumn('tour_name', function($data){
                    return '<a href="'.route('tour.show',['tour' => $data->tour , 'slug' => Str::slug($data->tour->name)]).'">'.$data->tour->name.'</a>';
                })
                ->addColumn('nominal', function($data){
                    return 'Rp. '.substr(number_format($data->amount, 2, ',', '.'),0,-3);
                })
                ->addColumn('expired', function($data){
                    return date('d F Y, H:i:s', strtotime($data->expired));
                })
                ->addColumn('action', function($data){
                    return '<a href="'. route('order.show', $data->id) .'" title="'. __('Detail') .'" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>';
                })
                ->rawColumns(['action','tour_name', 'nominal', 'expired'])
                ->make(true);
        }
    }

    public function tourGallery($id)
    {
        $tour = Tour::find($id);
        return response()->json([
            'success'   => true,
            'message'   => __('alert.success-get', ['attribute' => 'images']),
            'data'      => $tour->galleries
        ],200);
    }

    public function users()
    {
        return datatables()->of(User::with('role')->get())
            ->addColumn('email', function($data){
                return '<a href="'.route('user.show',$data->id).'">'.$data->email.'</a>';
            })
            ->addColumn('created_at', function($data){
                return date('d F Y, H:i:s', strtotime($data->created_at));
            })
            ->addColumn('action', function($data){
                return '<a href="'. route('user.edit', $data->id) .'" title="'. __('Edit') .'" class="btn btn-sm btn-success">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-danger delete" data-id="'. $data->id .'" title="'. __('Delete') .'">
                            <i class="fas fa-trash"></i>
                        </button>';
            })
            ->rawColumns(['action','email','created_at'])
            ->make(true);
    }
}
