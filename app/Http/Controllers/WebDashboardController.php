<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class WebDashboardController extends Controller
{


    public function index()
    {
        return view('dashboard.index');
    }
    
   
    
    public function adminIndex(Request $request)
    {
        $adminUsers = User::where('user_type', 'admin')->get();
        return response()->json (['Admins' =>$adminUsers]);
    }
    
    public function userIndex(Request $request)
    {
        $Users = User::where('user_type', 'user')->get();
        return response()->json (['Users' =>$Users]);
    }
    
   

    public function indexShops()
    {
        $shops = Shop::all();
        return response()->json($shops);
    }

 

    public function indexProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }   

    }
