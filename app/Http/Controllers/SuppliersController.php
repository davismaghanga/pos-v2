<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;

class SuppliersController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'userAccess']);
    }

    public function getIndex(){
        return view('suppliers');
    }

    public function postCreate(Request $request) {
        //

        $data = array(  'name' => $request->input('name'),
                        'handler' => $request->input('handler'),
                        'address' => $request->input('address'),
                        'phone' => $request->input('phone'));

        $validator = Validator::make($request->all(), [
            'phone' => 'size:10'
        ], [
            'phone.size' => 'Phone number must be 10 characters'
        ]);

        if($validator->fails()){
            return redirect('suppliers')
                ->withErrors($validator)
                ->withInput()
                ->with('createSupplier', true);
        }

        $supplier = new Supplier();
        $supplier->business = session('business_id');
        $supplier->name = $data['name'];
        $supplier->handler = $data['handler'];
        $supplier->address = $data['address'];
        $supplier->phone = $data['phone'];
        $supplier->save();

        return redirect('suppliers');
    }

}
