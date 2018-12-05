<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class SysAuthController extends Controller
{
    public function authenticate(Request $request)
    {


        $data = array(  'username' => $request->input('username'),
                        'password' => $request->input('password'),
                        'access' => 1);
        if(Auth::attempt($data)) {
            session(['admin_id' => Auth::user()->id]);
            return redirect()->intended('admin/home');
        }
        return redirect('admin')->with('adminLoginError', 'The login details provided are not correct. Try again.');
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect('admin');
    }
}
