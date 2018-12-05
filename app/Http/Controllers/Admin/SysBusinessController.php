<?php

namespace App\Http\Controllers\Admin;

use App\Business;
use App\Http\Controllers\Controller;
use App\SmsUnitsUpdate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class SysBusinessController extends Controller
{
    public function index(){
        return View::make('admin.businesses.businesses')->with('businesses',Business::all())->with('page','All businesses');
    }

    public function updateUnits(Request $request)
    {

        $business=Business::find(request('business_id'));

        if($business==null){
            return response()->json([
                'success'=>false,
                'message'=>'Invalid business'
            ]);
        }

        if(!$request->has('delta_units')){
            return response()->json([
                'success'=>false,
                'message'=>'Invalid number of units provided'
            ]);
        }

        $update=new SmsUnitsUpdate();
        $update->changer_id=Auth::id();
        $update->delta_units=request('delta_units');
        $update->pre_units=$business->units_consumed;
        $update->business_id=$business->id;
        $update->post_units=$business->units_consumed+request('delta_units');
        $update->Save();

        $business->units_consumed=$update->post_units;
        $business->Save();

        return response()->json([
            'success'=>true
        ]);

    }

    public function fetchUpdatesHistory()
    {
        return response()->json([
            'data'=>SmsUnitsUpdate::where('business_id',request('business_id'))->with('Changer')->get()
        ]);
    }
}
