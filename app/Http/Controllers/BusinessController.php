<?php

namespace App\Http\Controllers;

use App\Business;
use App\Facades\SMS;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class BusinessController extends Controller
{
    //

    public function register(Request $request) {
        switch ($request->input('stage')) {
            case 'a':
                switch (session('registerBusinessStep')){
                    case 'b':
                        return view('register_business_b');
                    break;
                    case 'c':
                        return view('register_business_c');
                    break;
                    case 'd':
                        return redirect()->intended('home');
                    break;
                }
                $data = array( 'name' => $request->input('name'),
                                'phone' => $request->input('phone'),
                                'email' => $request->input('email'),
                                'location' => $request->input('location'));

                $validator = Validator::make($request->all(), [
                    'email' => 'unique:businesses',
                    'phone' => 'size:10'
                ], [
                    'email.unique' => 'This email is already registered',
                    'phone.size' => 'Phone number must be 10 characters'
                ]);

                if($validator->fails()){
                    return redirect('login')
                        ->withErrors($validator)
                        ->withInput()
                        ->with('register', true);
                }

                $business = new Business();
                $business->name = $data['name'];
                $business->phone = $data['phone'];
                $business->email = $data['email'];
                $business->location = $data['location'];
                $business->save();

                session(['registerBusinessStep' => 'b']);
                return view('register_business_b')->with('business', $business->id);
                break;
            case 'b':
                switch (session('registerBusinessStep')){
                    case 'c':
                        return view('register_business_c');
                        break;
                    case 'd':
                        return redirect()->intended('home');
                        break;
                }
                $data = array(  'fullname' => $request->input('name'),
                                'username' => $request->input('username'),
                                'phone' => $request->input('phone'),
                                'password' => $request->input('password'),
                                'business' => $request->input('business'));

                $admin = new User();
                $admin->name = $data['fullname'];
                $admin->username = $data['username'];
                $admin->phone = $data['phone'];
                $admin->password = bcrypt($data['password']);
                $admin->business = $data['business'];
                $admin->level = 1;
                $admin->save();

                session(['registerBusinessStep' => 'c']);
                return view('register_business_c', ['business' => $data['business'], 'user' => $admin->id, 'pwd' => $data['password']]);
                break;
            case 'c':
                switch (session('registerBusinessStep')){
                    case 'd':
                        return redirect()->intended('home');
                        break;
                }
                $data = array(  'plan_id' => $request->input('pay_plan'),
                                'business' => $request->input('business'),
                                'user' => $request->input('reg_user_id'),
                                'pwd' => $request->input('reg_user_pwd'));
                $business = Business::find($data['business']);
                $business->pay_plan = $data['plan_id'];
                $business->save();

                //login the admin
                $user = User::find($data['user']);
                $user->status = 1;
                $user->save();

                Auth::login($user);

                session([   'user_id' => $data['user'],
                            'business_id' => $data['business']]);

                //send the reg details to the user
                $message = "%s. \n Login details \n Business code: %d \n Username: %s \n Password: %s \n Login at http://koowa.co.uk/htcPOS";
                $message = sprintf($message, $user->name, $business->id, $user->username, $data['pwd']);

                $response = SMS::send_user_intro($message, $user->phone);

                session(['registerBusinessStep' => 'd']);
                return redirect()->intended('home')->with('message', "Your login details have been sent to " . $user->phone . ".");
        }
    }
}
