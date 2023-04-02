<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create() {
        return view('users.register');
    }

    public function login() {
        return view('users.login');
    }

    public function register_user(Request $request) {
        $fields = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);

        $user = User::create($fields);
        Auth::login($user);

        return redirect('/')->with('message', 'Registration OK');
    }

    public function login_user(Request $request) {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($fields)){
            $request->session()->regenerate();
            return redirect('/')->with('message', 'Login OK');
        }

        return back()->withErrors(['email' => 'invalid credentials', 'password' => 'invalid password']);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'you have been loguot');
    }

    public function save_api_credentials(Request $request){

        $fields = $request->all();

        $fields['api_key'] = $fields['api_key'];
        $fields['api_secret'] = $fields['api_secret'];
        
        User::where('id', $fields['user_id'])->update([
            'api_key' => $fields['api_key'],
            'api_secret' => $fields['api_secret'],
        ]);

        //$row = User::where('id', $fields['user_id'])->first();
        //User::where('id', $fields['user_id'])->first();

        return response()->json(['res' => $request->all()]);
    }
}
