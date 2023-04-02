<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Libs\BinanceConnector\BinanceConnector;
use Illuminate\Support\Facades\Storage;
use function \Ratchet\Client\connect as socket_connect;

class ProfileController extends Controller
{
    public function home(){

        return view('layouts.profile');
    }

    public function create_order(Request $request){

        $order_fields = $request->all();
        $order_fields['order_status'] = "pending";
        $res = Order::create($order_fields);
        $orders = Order::all()->where('user_id', $order_fields['user_id']);
        return response()->json(['orders' => $orders]);
    }

    public function delete_order(Request $request){

        $order_id = $request->get('order_id');
        $user_id = $request->get('user_id');
        Order::destroy($order_id);
        $orders = Order::all()->where('user_id', $user_id);
        return response()->json(['orders' => $orders]);
    }

    public function api_settings(){
        
        return view('profile.api_settings');
    }

    public function my_orders(){

        $orders = Order::all()->where('user_id', 1);

        return view('profile.my_orders', ['orders' => $orders]);
    }

    public function get_user_orders(Request $request){

        $user_id = $request->get('currentUserId');
        $orders = Order::all()->where('user_id', $user_id);

        return response()->json(['orders' => $orders]);
    }
}

