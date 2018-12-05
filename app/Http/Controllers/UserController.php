<?php

namespace App\Http\Controllers;

use App\Facades\SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware(['auth', 'userAccess']);
    }
    public function reset(Request $request){
        $user = User::find($request->input('user_id'));


        $validator=Validator::make($request->all(),[
            'new_password'=>'required|confirmed'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->password=Hash::make($request->input('new_password'));
        $user->Save();

        $msg=$user->name . "password has been changed to" . $request->input('new_password');
        if($request->has('admin_send')){
            $users = User::where([['business', session('business_id')],['level','<',1]])->get();

            foreach ($users as $user){
                SMS::send($msg, $user->phone,session('business_id'));
            }

        }
        if($request->has('user_send')){
            $users=User::where([['business',session('business_id')],['level','>',0]])->get();
            foreach ($users as $user){
                SMS::send($msg,$user->phone,session('business_id'));
            }
        }




        return redirect()->back()->withErrors(new MessageBag(['done'=>'Password Successfully Changed']));
    }

    public function getIndex(){
        return view('users');
    }

    public function postRegister(Request $request) {
        $data = array(  'fullname' => $request->input('name'),
                        'username' => $request->input('username'),
                        'phone' => $request->input('phone'),
                        'password' => $request->input('password'),
                        'business' => $request->input('business'),
                        'level' => $request->input('level'));

        $validator = Validator::make($request->all(), [
            'repassword' => 'same:password',
            'phone' => 'size:10'
        ], [
            'repassword.same' => 'Passwords do not match.',
            'phone.size' => 'Phone number must be 10 characters'
        ]);

        if($validator->fails()){
            return redirect('users')
                ->withErrors($validator)
                ->withInput()
                ->with('register', true);
        }

        $user = new User();
        $user->name = $data['fullname'];
        $user->username = $data['username'];
        $user->phone = $data['phone'];
        $user->password = bcrypt($data['password']);
        $user->business = $data['business'];
        $user->level = $data['level'];
        $user->save();

        return redirect('users');
    }

    public function getLogout(){
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}
