<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    //
    private  $client;

    public function __construct()
    {
        $this->client=Client::find(3);
    }

    public function authenticate(Request $request)
    {
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required',
            'business_code'=>'required'
        ]);


        $data = ['business' => $request->input('business_code'),
            'username' => $request->input('username'),
            'password' => $request->input('password')];
        if(!Auth::attempt($data)) {
            $errors=array();
            $errors["business"]=['The business code is incorrect to this user'];
            return response()->json([
                'message'=>'The given data was invalid',
                'errors'=>$errors
            ],422);
        }

        $params=[
            'grant_type'=>'password',
            'client_id'=>$this->client->id,
            'client_secret'=>$this->client->secret,
            'username'=>request('username'),
            'password'=>request('password'),
            'scope'=>'*'
        ];


        $request->request->add($params);

        $proxy=Request::create('oauth/token','POST');

        return Route::dispatch($proxy);
        
    }
}
