<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;

class TestController extends Controller
{
    public function index(){
        //Cache::put('fooQQ', 'bar1');
        
        return view('tests', ['base_url' => App::make('url')->to('/')]);
        //return view('tests', ['key1' => Cache::get('fooQQ')]);
    }
}
