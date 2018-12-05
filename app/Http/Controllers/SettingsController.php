<?php

namespace App\Http\Controllers;

use App\Business;
use App\Category;
use App\Http\Requests;
use App\SysSmsRequest;
use App\TaxEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'userAccess']);
    }

    public function getIndex()
    {
        return view('settings')->with('tab', 1);
    }

    public function postUploadLogo(Request $request) {
        $validator = Validator::make($request->all(), [
            'logo' => 'image'
        ], [
            'logo.image' => 'The file must be an image'
        ]);
        if($validator->fails()){
            return redirect('settings')
                ->withErrors($validator);
        }

        $file = $request->file('logo');
        $filename = 'logo-' . session('business_id') . '.' . $file->getExtension();
        $success = $file->move('images', $filename);

        if ($success){
            $business = Business::find(session('business_id'));
            $business->logo = $filename;
            $business->save();
        }

        return redirect('settings')->with('tab', 1);
    }

    public function postBasic(Request $request) {
        $data = array(  'name' => $request->input('name'),
                        'phone' => $request->input('phone'),
                        'email' => $request->input('email'));

        $validator = Validator::make($request->all(), [
            'phone' => 'size:10'
        ], [
            'phone.size' => 'Phone number must be 10 characters'
        ]);

        if($validator->fails()){
            return redirect('settings')
                ->withErrors($validator)
                ->withInput();
        }

        $business = Business::find(session('business_id'));
        $business->name = $data['name'];
        $business->phone = $data['phone'];
        $business->email = $data['email'];
        $business->save();

        return redirect('settings');
    }

    public function postLoyalty(Request $request){
        $data = array(  'has_loyalty' => $request->input('has_loyalty'),
                        'earn_rate' => $request->input('loyalty_earn_rate'),
                        'redeem_rate' => $request->input('loyalty_redeem_rate'),
                        'minimum_redeemable_points' => $request->input('minimum_redeemable_points'),
                        'min_earn' => $request->input('loyalty_min_earn'));
        $validator = Validator::make($request->all(), [
            'loyalty_earn_rate' => 'numeric',
            'loyalty_redeem_rate' => 'numeric',
            'loyalty_min_earn' => 'numeric',
        ]);

        if ($validator->fails()) {
            return redirect('settings')
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 2);
        }
        $business = Business::find(session('business_id'));
        $business->has_loyalty = $data['has_loyalty'] == 'on' ? 1 : 0;
        $business->loyalty_earn_rate = $data['earn_rate'];
        $business->loyalty_redeem_rate = $data['redeem_rate'];
        $business->loyalty_min_earn = $data['min_earn'];
        $business->minimum_redeemable_points = $data['minimum_redeemable_points'];
        $business->save();

        return redirect('settings')->with('tab', 2);
    }

    public function postSms(Request $request) {
        $business = Business::find(session('business_id'));
        $business->sms_greeting = $request->input('sms_greeting');
        $business->sms_extension = $request->input('sms_extension');
        $business->save();
        return redirect('settings')->with('tab', 3);
    }

    public function getSmsCancelCustom() {
        $business = Business::find(session('business_id'));
        $business->sms_has_custom = false;
        $business->save();
        return redirect('settings')->with('tab', 3);
    }

    public function postSmsCustomHandle(Request $request) {
        $req = new SysSmsRequest();
        $req->business = session('business_id');
        $req->handle = $request->input('sms_sender');
        $req->save();
        return redirect('settings')->with('tab', 3);
    }

    public function getDiscountForCategory($cat) {
        $category = Category::find($cat);
        return $category->discount;
    }

    public function postSetDiscount(Request $request) {
        $data = array(  'disc' => $request->input('discount'),
                        'cat' => $request->input('discount_category'));

        $validator = Validator::make($request->all(), [
            'discount' => 'numeric|max:99',
            'discount_category' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('settings')
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 4);
        }

        $category = Category::find($data['cat']);
        $category->has_discount = true;
        $category->discount = ($data['disc'] / 100);
        $category->save();

        return redirect('settings')->with('tab', 4);
    }

    public function postSetTax(Request $request) {
        $data = array(  'cat' => $request->input('tax_category'),
                        'type' => $request->input('tax_type'),
                        'incl' => $request->input('tax_is_inclusive'),
                        'tax' => $request->input('tax'));

        $validator = Validator::make($request->all(), [
            'tax' => 'numeric|max:99',
            'tax_category' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('settings')
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 5);
        }

        $cat = Category::find($data['cat']);
        $cat->tax_name = $data['type'];
        $cat->tax = $data['tax'] / 100;
        $cat->tax_is_inclusive = $data['incl'] == 'on';
        $cat->save();

        return redirect('settings')->with('tab', 5);
    }

    public function getTaxForCategory($cat) {
        $category = Category::find($cat);
        $result = array($category->tax_name, $category->tax, $category->tax_is_inclusive);
        return $result;
    }
}
