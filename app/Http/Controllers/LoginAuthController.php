<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;


class LoginAuthController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return view('dashboard');
        }else{
            return view('login');
        }
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password','is_admin');

        if (Auth::attempt($credentials)) {
            // return redirect()->intended('dashboard')
            // ->withSuccess('Signed in');
              return redirect()->intended('dashboard')
            ->with('message','Signed in');
        }
   // return redirect("dashboard")->withSuccess('You have signed-in');
        return redirect("/")->with('error','Login data is in-correct');
        
    }
    public function dashboard()
    {

        if(Auth::check()){
            return view('dashboard');
        }

        return redirect("/")->withSuccess('You are not allowed to access');
    }
    public function logOut() {

        Session::flush();
        Auth::logout();

        return Redirect('/');
    }

}