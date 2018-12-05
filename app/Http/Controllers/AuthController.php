<?php
/**
 * Created by PhpStorm.
 * User: NJORO
 * Date: 11/4/2016
 * Time: 5:05 PM
 */

namespace App\Http\Controllers;



use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
//        $u = new User();
//        $u->business='2';
//        $u->username='demo';
//        $u->password=bcrypt('demo');
//        $u->name='user';
//        $u->phone='0700';
//        $u->level='1';
//        $u->Save();

        //Session::flush();

        if (session(['current_sale_id'])){
            session(['current_sale_id' => 0]);
        }

        $data = array(  'business' => $request->input('login_business_code'),
                        'username' => $request->input('login_username'),
                        'password' => $request->input('login_user_password'));
        if(Auth::attempt($data)) {
            session([   'user_id' => Auth::user()->id,
                        'business_id' => $data['business']]);
            return redirect()->intended('home');
        }
        return redirect('login')->with('loginError', 'The login details provided are not correct. Try again.');
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect('login');
    }
}