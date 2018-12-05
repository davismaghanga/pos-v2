<?php

namespace App\Http\Controllers\Api\Sales;

use App\Customer;
use App\Product;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    //

    public function products(Request $request)
    {
        //TODO check whether auth user is allowed to get products for this business id
        $this->validate($request,[
            'business_code'=>'required'
        ]);

        $products = Product::where('business', request('business_code'))->get();
        $services = Service::where('business', request('business_code'))->get();

        return response()->json([
            'products'=>$products,
            'services'=>$services
        ]);
    }

    public function getCustomer(Request $request)
    {
        $this->validate($request,[
            'business_code'=>'required',
            'phone_number'=>'required'
        ]);

        $customer=Customer::where('phone',request('phone_number'))->where('business',request('business_code'))->first();
        if($customer==null){
            return response()->json([
                'success'=>false,
            ]);
        }

        return response()->json([
            'success'=>true,
            'customer'=>$customer
        ]);
    }


    public function completeSale(Request $request)
    {

        $this->validate($request,[
            'sale_items'=>'required',
            'business_code'=>'required',
        ]);

        //receive an array of sale items,
        //create a sale with the sale items
        //process the charge
    }
}
