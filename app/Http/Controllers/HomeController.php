<?php

namespace App\Http\Controllers;

use App\Package;
use App\Company;
use App\Gallery;
use App\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        $testimonies = Order::whereRating(5)->get();
        $company = Company::find(1);
        $portofolios = Gallery::where('is_portofolio',1)->take(6)->get();
        return view('index',compact('packages', 'company', 'testimonies', 'portofolios'));
    }
}
