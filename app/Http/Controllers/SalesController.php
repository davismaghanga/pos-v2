<?php

namespace App\Http\Controllers;

use App\Business;
use App\Customer;
use App\Facades\SMS;
use App\PaymentConfirmation;
use App\Product;
use App\Receipt;
use App\Sale;
use App\SaleEntry;
use App\Service;
use App\TaxEntry;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SalesController extends Controller
{

    /**
     * Sale status
     * 0 = incomplete
     * 1 = complete
     * 2 = cancelled
     * Receipts status
     * 0 = unused
     * 1 = used
     * 2 = reversed
     * Channels
     * 0 = mpesa
     * 1 = cash
     * 2 = visa
     */

    public function __construct()
    {
        $this->middleware(['auth', 'userAccess']);
    }

    public function getIndex()
    {
        return view('sales');
    }

    private function addItem($data)
    {

//        return response()->json($data);
        //det if product or service
        $id_type_pair = explode('-', $data['product']);
        //get product and price
        $product = $id_type_pair[1] == 'product' ? Product::find($id_type_pair[0]) : Service::find($id_type_pair[0]);
        $price = $id_type_pair[1] == 'product' ? $product->price : $product->charge;

        //find sale
        $sale = Sale::find(session('current_sale_id'));

        //check if exists and add quantity instead
        $existing_Q = SaleEntry::where(['sale' => session('current_sale_id'),
            'is_service' => ($id_type_pair[1] == 'service'),
            'product' => $id_type_pair[0]]);

        if ($existing_Q->exists()) {
            $sale_item = $existing_Q->first();
            $sale_item->quantity += $data['quantity'];

        } else {
            //if not existing create new
            $sale_item = new SaleEntry();
            $sale_item->sale = session('current_sale_id');
            $sale_item->is_service = ($id_type_pair[1] == 'service');
            $sale_item->product = $id_type_pair[0];
            $sale_item->quantity = $data['quantity'];
        }

        //if is product return if stock is not enough
        if ($id_type_pair[1] == 'product') {
            if ($product->stock < $sale_item->quantity){
                return redirect('sales')->with('stockNotEnough', 'Only ' . $product->stock . ' items of ' . $product->name . ' are remaining');
            }
        }


        //if is product return if stock is not enough
        if ($id_type_pair[1] == 'service') {
            if ($product->stock < $sale_item->quantity){
                return redirect('sales')->with('stockNotEnough', 'Only ' . $product->stock . ' items of ' . $product->name . ' are remaining');
            }
        }
        $sale_item->save();

        //check for discount
        if ($product->Category->has_discount){
            $sale->discount += floor($product->Category->discount * $price * $data['quantity']);
        }

        //check for taxes
        if ($product->Category->tax_is_inclusive) {
            $sale->tax_in += floor($product->Category->tax * $price * $data['quantity']);
        } else {
            $sale->tax_ex += floor($product->Category->tax * $price * $data['quantity']);
        }

        //add price to total
        $sale->amount += $price * $data['quantity'];
        $sale->save();

        return redirect('sales');
    }

    public function postAddProduct(Request $request)
    {
        //check if product is null or quantity is not an int
        if (!$request->input('product') || $request->input('product') == 'NULL' || $request->input('quantity') < 1) {
            return redirect('sales');
        }

        $data = array('product' => $request->input('product'),
            'quantity' => $request->input('quantity'));

        if (!session('current_sale_id') || session('current_sale_id') == '0') {
            $sale = new Sale();
            $sale->business = session('business_id');
            $sale->by_user = session('user_id');
            $sale->status = 0;
            $sale->save();
            session(['current_sale_id' => $sale->id]);
            return $this->addItem($data);
        } else {
            return $this->addItem($data);
        }
    }

    public function postSetCustomer(Request $request)
    {
        $data = array(  'phone' => $request->input('phone'),
            'name' => $request->input('name'),
            'channel' => $request->input('channel'),
            'amount' => $request->input('amount'),
            'code' => $request->input('mpesa_code'),
            'visa' => $request->input('visa_code'),
            'hours'=> $request->input('hours'),
            'minutes'=> $request->input('minutes'));

        //validate
        $validator = Validator::make($request->all(), [
            'channel' => 'required',
        ], [
            'channel.required' => 'Please select a payment method.',
            'phone.size' => 'Phone number must be 10 digits'
        ]);

        if ($validator->fails()) {
            return redirect('sales')
                ->withErrors($validator)
                ->withInput()
                ->with('setCustomer', true);
        }

        //check if customer exists
        $customer = Customer::where(['business' => session('business_id'), 'phone' => $data['phone']]);
        if ($customer->exists()){
            $customer = $customer->first();

        }else{
            //register the customer
            $newCust = new Customer();
            $newCust->business = session('business_id');
            $newCust->name = $data['name'];
            $newCust->phone = $data['phone'];
            $newCust->save();

            $customer = $newCust;
        }

        if($data['channel']==3){
            $business=Business::find(session('business_id'));
            if($data['amount']>$customer->loyalty_points/$business->loyalty_redeem_rate){
                return redirect('sales')
                    ->withErrors(['amount'=>'The amount could not be covered by your loyalty Max: Ksh' . $customer->loyalty_points])
                    ->withInput()
                    ->with('setCustomer', true);
            }
            $redeemed_points=$data['amount']*$business->loyalty_redeem_rate;
        }


        $sale = Sale::find(session('current_sale_id'));
        $total = $sale->amount - $sale->discount + $sale->tax_ex;
        $bal = $data['amount'] - $total;
        $points = 0;

        switch ($data['channel']) {
            case 0:
                //visa

                //TODO: check visa entry

                //add a new receipt
                $receipt = new Receipt();
                $receipt->business = session('business_id');
                $receipt->channel = 3;
                $receipt->code = $data['visa'];
                $receipt->customer = $sale->customer;
                $receipt->cashier=Auth::id();
                $receipt->sale = session('current_sale_id');
                $receipt->amount = $data['amount'];
                $receipt->status = 1;
                $receipt->save();

                break;
            case  1:
                //mpesa

                //add a new receipt
                $receipt = new Receipt();
                $receipt->business = session('business_id');
                $receipt->channel = 2;
                $receipt->code = $data['code'];
                $receipt->customer = $customer->id;
                $receipt->sale = session('current_sale_id');
                $receipt->amount = $data['amount'];
                $receipt->status = 1;
                $receipt->cashier=Auth::id();
                $receipt->save();

                if($request->has('t_id') && $request->input('t_id')!=""){
                    $payconf=PaymentConfirmation::find($request->input('t_id'));
                    $payconf->state=1;
                    $payconf->Save();
                }

                break;
            case 2:
                //cash

                $receipt = new Receipt();
                $receipt->business = session('business_id');
                $receipt->sale = session('current_sale_id');
                $receipt->channel = 1;
                $receipt->code = 0;
                $receipt->amount = $data['amount'];
                $receipt->customer = $customer->id;
                $receipt->status = 1;
                $receipt->cashier=Auth::id();
                $receipt->save();

                break;

            case 3:
                //loyalty
                $receipt = new Receipt();
                $receipt->business = session('business_id');
                $receipt->sale = session('current_sale_id');
                $receipt->channel = 4;
                $receipt->code = 0;
                $receipt->amount = $data['amount'];
                $receipt->customer = $customer->id;
                $receipt->status = 1;
                $receipt->cashier=Auth::id();
                $receipt->save();

                //reduce customer loyalty points
                $customer->loyalty_points-=$redeemed_points;
                $customer->Save();

                break;

        }



        //increase customer balance
        $customer->balance += $bal > 0 ? 0 : $bal;
        $customer->save();

        //set customer
        $sale->customer = $customer->id;

        //increase sale balance
        $sale->balance += $bal > 0 ? 0 : $bal;
        $sale->status = 1;
        $sale->save();

        //update receipt balance
        $receipt->balance+= $bal > 0 ? 0 : $bal;
        $receipt->Save();

        //set the schedule
        $sale->job_status=1;
        $sale->processing_time=($data['hours']*60)+$data['minutes'];
        
        $sale->expected_completion=Carbon::now()->addHours($data['hours'])->addMinutes($data['minutes']);
        $sale->Save();

        //add customer points
        session(['loyalty_points'=>0]);
        $business = Business::find(session('business_id'));
        if ($sale->amount >= $business->loyalty_min_earn && $business->loyalty_earn_rate > 0){
            $points = floor($data['amount'] / $business->loyalty_earn_rate);
            $customer->loyalty_points += $points;
            $customer->save();
            session(['loyalty_points'=>$points]);
        }

        $sale_items = SaleEntry::where(['sale' => session('current_sale_id'), 'status' => 0])->get();

        //send sms
        $msg = " " . $customer->name . " your order \n";
        $msg .= "Item | Qnty | Price \n";
        $items = "";
        foreach ($sale_items as $item){
            $a = ($item->is_service ? $item->Product->charge : $item->Product->price) * $item->quantity;
            $i = $item->Product->name . " (x" . $item->quantity . ") : " . $a . "\n";
            $items .= $i;
        }
        $msg .= $items;
        $msg .= "Discount: " . $sale->discount . "\nTotal: " . $total . "\nPaid: " . $data['amount'] . "\nBalance: " . $bal. "\nTotal Points: " . $customer->loyalty_points . " \n";
        $msg .=" Collect on " . $sale->expected_completion;


       SMS::send($msg, $customer->phone);

        //reduce stock for products
        foreach ($sale_items as $item) {
            if (!$item->is_service){
                $product = Product::find($item->product);
                $product->stock -= $item->quantity;
                $product->save();
            }else{
                $product = Service::find($item->product);
                $product->stock -= $item->quantity;
                $product->save();
            }
        }

        session(['current_receipt'=>$receipt->id]);
        session(['current_sale_id' => 0]);



        return $bal < 0 ? redirect('sales') : redirect('sales')->with('hasChange', $bal);

    }

    public function getReceipt() {
        //
    }

    public function getCancel(){
        if (session('current_sale_id') && session('current_sale_id') != '0') {
            $sale = Sale::find(session('current_sale_id'));
            $sale->status = 2;
            $sale->save();

            session(['current_sale_id' => 0]);
        }

        return redirect('sales');
    }

    public function getUserName($no) {
        $customer = Customer::where(['business' => session('business_id'), 'phone' => $no]);
        if ($customer->exists()){
            $customer = $customer->first();
            return [0, $customer->name];
        }
        return [1, 'Customer does not exist. Provide a name'];
    }

    public function getDeleteEntry($id) {
        $entry = SaleEntry::find($id);
        $this->reduce($id, $entry->quantity);
        $this->delete($id);
        return redirect("sales");
    }

    private function delete($id) {
        SaleEntry::destroy($id);
    }

    public function getIncreaseEntry($id) {
        $entry = SaleEntry::find($id);
        $type = $entry->is_service ? "-service" : "-product";
        $data = array( "product" => $entry->product . $type,
                        "quantity" => 1);
        $this->addItem($data);
        return redirect("sales");
    }

    public function getReduceEntry($id) {
        $entry = SaleEntry::find($id);

        $this->reduce($id);

        if ($entry->quantity == 1) {
            $this->delete($id);
        } else{
            $entry->quantity--;
            $entry->save();
        }

        return redirect('sales');
    }

    private function reduce($id, $quantity = 1) {
        $entry = SaleEntry::find($id);
        $sale = $entry->Sale;
        $product = $entry->Product;
        $price = $entry->is_service ? $product->charge : $product->price;

        //check for discount
        if ($product->Category->has_discount){
            $sale->discount -= floor($product->Category->discount * $price * $quantity);
        }

        //check for taxes
        if ($product->Category->tax_is_inclusive) {
            $sale->tax_in -= floor($product->Category->tax * $price * $quantity);
        } else {
            $sale->tax_ex -= floor($product->Category->tax * $price * $quantity);
        }

        //add price to total
        $sale->amount -= $price * $quantity;
        $sale->save();
    }

    public function getCutomerLoyalty(Request $request)
    {
        $customer=Customer::where('phone',request('number'))->first();
        if($customer==null){
            return response()->json([
               'success'=>false,
                'message'=>'customer not found'
            ]);
        }

        //if customer hs no loyalty points return false
        if($customer->loyalty_points<=0){
            return response()->json([
                'success'=>false,
                'message'=>'The customer has no loyalty points',
                'customer'=>$customer
            ]);
        }

        //check the minimum redeemable points and return false if the points hazijafika
        $business=$customer->Business;

        if($business==null){
            return response()->json([
                'success'=>false,
                'message'=>'Business information was not found',
                'customer'=>$customer
            ]);
        }

        if($customer->loyalty_points<$business->minimum_redeemable_points){
            return response()->json([
                'success'=>false,
                'message'=>'The customer has not attained minimum redeemable points' . $business->minimum_redeemable_points,
                'customer'=>$customer
            ]);
        }

        return response()->json([
            'success'=>true,
            'message'=>'Redeem customer points',
            'customer'=>$customer,
            'business'=>$business,
            'points_worth'=>$customer->loyalty_points/$business->loyalty_redeem_rate
        ]);

    }
}
