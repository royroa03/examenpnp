<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest',['only' => 'showLoginForm']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showLoginForm()
    {
        return view('login.index');
    }

    public function login()
    {
        $credentials=$this->validate(request(),[
            'email' => 'email|required|string',
            'password' => 'required|string'
        ]);
        //return $credentials;

       if(Auth::attempt($credentials)){
           return redirect()->route('dashboard');
       }

       return back()->withErrors(['email' => 'Estas credenciales no concuerdan con nuestros registros'])
           ->withInput(request(['email']));
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
