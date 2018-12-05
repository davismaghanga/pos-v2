<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Customer;

use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;
use Prophecy\Exception\Exception;

class CustomersController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'userAccess']);
    }

    public function getIndex(){
        return view('customers');
    }

    public function postCreate(Request $request) {
        //

        $data = array(  'name' => $request->input('name'),
                        'phone' => $request->input('phone'));

        $validator = Validator::make($request->all(), [
            'phone' => 'size:10'
        ], [
            'phone.size' => 'Phone number must be 10 characters'
        ]);

        if($validator->fails()){
            return redirect('customers')
                ->withErrors($validator)
                ->withInput()
                ->with('createCustomer', true);
        }

        if($request->has('id') && $request->input('id')!=null){
            $customers=Customer::find($request->input('id'));
        }else{
            $customers = new Customer();
        }
        $customers->business = session('business_id');
        $customers->name = $data['name'];
        $customers->phone = $data['phone'];
        $customers->save();

        return redirect('customers');
    }

    public function postSetCustomer(Request $request) {

    }

    public function postUploadExcel(Request $request) {
        $validator = Validator::make($request->all(), [
            'excel' => 'mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ], [
            'excel.mimetypes' => 'The file must be an excel file'
        ]);
        if($validator->fails()){
            return redirect('customers')
                ->withErrors($validator);
        }

        $file = $request->file('excel');
        $filename = 'excel-import-' . session('business_id') . '.' . $file->getClientOriginalExtension();
        $success = $file->move("imports", $filename);


        Excel::load("imports/" . $filename, function($reader) {
            $results = $reader->get();

            $results->chunk(300)->each(function ($ch){
                $valueString = '';
                $now = Carbon::now()->format("Y-m-d H:m:s");
                foreach ($ch as $value) {
                    $phone = "0" . $value->phone;
                    $valueString .= '("' . $now . '","' . $now . '","' . session('business_id') . '","' . $value->name . '","' . $phone . '","' . $value->balance . '","' . $value->loyalty . '"), ';
                }
                $valueString = rtrim($valueString, ", " );
                try {
                    DB::insert( "INSERT INTO customers (`created_at`, `updated_at`, `business`, `name`, `phone`, `balance`, `loyalty_points`) VALUES $valueString" );
                } catch (Exception $e) {
                    print_r([$e->getMessage()]);
                }
            });
            echo "Loading... Please Wait, you will be redirected when we are done!";
        });

        return redirect('customers');
    }

    public function postGetCustomerName(Request $request) {
        return "hehehe";
    }

    public function postSearch(Request $request) {
        $value = $request->input('search');
        $result = Customer::where('name','like', '%' . $value . '%')->orWhere('phone','like', '%' . $value . '%')->take(100)->get();

        return redirect('customers')->withInput()->with('customers', $result);
    }

    public function getDelete($id) {
        Customer::destroy($id);
        return redirect('customers');
    }

    public function getExportexcel(){
            $file=Excel::create('contacts_export', function($excel) {
                // Set the title
                $excel->setTitle('UzaPlus POS contacts');
                // Chain the setters
                $excel->setCreator('_system auto generate')
                    ->setCompany('uzaplus');
                // Call them separately
                $excel->sheet('contacts', function($sheet) {
                    $Customers=Customer::where('business', session('business_id'))->select(['name','phone'])->get();
                    $sheet->fromArray($Customers);

                });
            });

        $file->store('xls')->download();


    }

}
