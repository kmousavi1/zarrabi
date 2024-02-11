<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index () {
        return view('auth.login');
    }

    public function userCheck (LoginRequest $request){
        $user=User::where([['username','=', $request->username],['password' , '=' , $request->password]])->first();
        if ($user) {
            // Success
            Auth::login($user);
            return redirect()->route('home');
        } else {
            // Go back on error (or do what you want)
            $request->session()->flash('status', 'username or password is wrong');
            return redirect()->back();
        }
    }
}
