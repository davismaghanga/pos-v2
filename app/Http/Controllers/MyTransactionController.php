<?php

namespace App\Http\Controllers;

use App\PaymentConfirmation;
use Illuminate\Http\Request;

class MyTransactionController extends Controller
{
    public function postMpesaTransactionData(Request $request)
    {
        $data=json_decode($request->getContent(), true);

        $transaction=new PaymentConfirmation();
        $transaction->trans_amount=$data['TransAmount'];
        $transaction->bill_ref_number=$data['BillRefNumber'];
        $transaction->trans_type=$data['TransactionType'];
        $transaction->trans_id=$data['TransID'];
        $transaction->trans_time=$data['TransTime'];
        $transaction->business_short_code=$data['BusinessShortCode'];
        $transaction->invoice_no=$data['InvoiceNumber'];
        $transaction->org_account_bal=$data['OrgAccountBalance'];
        $transaction->third_party_trans_id=$data['ThirdPartyTransID'];
//        $transaction->kyc_name=$data['FirstName']. " " .$data['LastName'];
        $names=array($data['FirstName'].",".$data['LastName']);
        $transaction->kyc_name=implode(",",$names);
        $transaction->msisdn=$data['MSISDN'];
        $transaction->save();
    }
}
