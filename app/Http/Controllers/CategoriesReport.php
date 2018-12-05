<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\SaleEntry;
use App\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class CategoriesReport extends Controller
{
    //
    public function getIndex()
    {
        $category=Category::where('business',session('business_id'))->get();
        return View::make('reports.categories_report')->with('categories',$category)->with('page','categories report');
    }

    public function postFilter(Request $request)
    {
        $sentence="";
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        //get all product ids where necessary
        $category=Category::find(request('category'));
        if($request->input('type')==1){
            $sentence.="Product report of " . $category->name . "category ";
            $ids=Product::where('category',$request->input('category'))->where('business',session('business_id'))->select('id')->get();
        }else{
            $sentence.="Service report of " . $category->name . " category ";

            $ids=Service::where('category',$request->input('category'))->where('business',session('business_id'))->select('id')->get();

        }

        $final_ids=array_flatten($ids->toArray());


        if($request->input('type')==1){
            $sales_items=SaleEntry::whereIn('product',$final_ids)->groupBy('created_at')->where('is_service',0)->whereBetween('created_at', [$startDate,$endDate])->get();
        }
        else{
            $sales_items=SaleEntry::whereIn('product',$final_ids)->where('is_service',1)->whereBetween('created_at', [$startDate,$endDate])->get();

        }
        return json_encode($sales_items);

        $sentence.=" from " . $startDate . "to" . $endDate;


//    return json_encode($sales_items);
        return redirect('categories_report')->withInput()->with('sentence',$sentence)->with('sales_items', $sales_items);


    }

    public function ExportExcelData(Request $request)
        {

            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'));

            //get all product ids where necessary

            if($request->input('type')==1){
                $ids=Product::where('category',$request->input('category'))->where('business',session('business_id'))->select('id')->get();
            }else{

                $ids=Service::where('category',$request->input('category'))->where('business',session('business_id'))->select('id')->get();

            }

            $final_ids=array_flatten($ids->toArray());


            if($request->input('type')==1){
                $sales_items=SaleEntry::whereIn('product',$final_ids)->where('is_service',0)->whereBetween('created_at', [$startDate,$endDate])->get();
            }
            else{
                $sales_items=SaleEntry::whereIn('product',$final_ids)->where('is_service',1)->whereBetween('created_at', [$startDate,$endDate])->get();
            }



            Session::flash('data',$sales_items);
            if ($request->has('excel')){
                //export excel
                $now=Carbon::now();
                $file=Excel::create('categoriesReport'.$now, function($excel) {

                    $excel->sheet('New sheet', function($sheet) {

                        $sheet->loadView('reports.categories_report_table',array('sales_items'=>session('data')));

                    });

                });
                $file->store('xls')->download();

            }

        }
    public function retrieveThem(Request $request)
    {
        $category=Category::find($request->input('category_id'));
        return Response::json([
            'result23'=>$request->all(),
            'category'=>$category
        ]);
    }
}
