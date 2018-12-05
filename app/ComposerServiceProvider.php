<?php

namespace App\Providers;

use App\Business;
use App\Category;
use App\Customer;
use App\Product;
use App\Receipt;
use App\Sale;
use App\SaleEntry;
use App\Service;
use App\Supplier;
use App\TaxEntry;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\SysPlan;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot() {
        View::composer('register_business_c', function ($view) {
            $plans = SysPlan::all();
            $view->with('plans', $plans);
        });

        View::composer('dashboard', function ($view) {
            $view->with('page', 'home');
        });

        View::composer('users', function ($view) {
            $user = Auth::user();
            $users = User::where('business', $user->business)->get();
            $view->with('users', $users)->with('user', $user)->with('page', 'users');
        });

        View::composer('products', function ($view) {
            $suppliers = Supplier::where('business', session('business_id'))->get();
            $categories = Category::where('business', session('business_id'))->get();
            if (session('products')){
                $products = session('products');
            }else {
                $products = Product::where('business', session('business_id'))->get();
            }

            $view   ->with('products', $products)
                ->with('categories', $categories)
                ->with('suppliers', $suppliers)->with('page', 'products');
        });

        View::composer('services', function ($view) {
            if (session('services')) {
                $services = session('services');
            }else{
                $services = Service::where('business', session('business_id'))->get();
            }
            $categories = Category::where('business', session('business_id'))->get();
            $view   ->with('services', $services)
                ->with('categories', $categories)->with('page', 'services');
        });

        View::composer('customers', function ($view) {
            if (session('customers')) {
                $customers = session('customers');
            }else {
                $customers = Customer::where('business', session('business_id'))->orderBy('id','desc')->take(500)->get();
            }
            $view->with('customers', $customers)->with('page', 'customers');
        });

        View::composer('suppliers', function ($view) {
            $suppliers = Supplier::where('business', session('business_id'))->get();
            $view->with('suppliers', $suppliers)->with('page', 'suppliers');
        });

        View::composer('sales', function ($view) {
            $products = Product::where('business', session('business_id'))->get();
            $services = Service::where('business', session('business_id'))->get();
            $sale_items = new Collection();
            if (session('current_sale_id') && session('current_sale_id') != 0) {
                $sale_items = SaleEntry::where(['sale' => session('current_sale_id'), 'status' => 0])->get();
            }
            $view   ->with('products', $products)
                    ->with('services', $services)
                    ->with('sale_items', $sale_items)->with('page', 'sales');
        });


        View::composer('receipt', function ($view) {
                $receipt=null;
                $sale=null;
                $sale_items=null;
                $business=null;
                $customer=null;
                if(session('current_receipt')!=""){
                    $receipt= Receipt::find(session('current_receipt'));
//                   //get the sales for this receipt
                    $sale=Sale::find($receipt->sale);
                    //get the sales items associated with this receipt
                    $sale_items = SaleEntry::where(['sale' =>$sale->id, 'status' => 0])->get();
                    $business = Business::find(session('business_id'));
                    $customer=Customer::where('id','=',$sale->customer)->first();



                }
                $view->with('receipt',$receipt)->with('sale',$sale)->with('sale_items',$sale_items)->with('business',$business)->with('customer',$customer);
        });

        View::composer('balances', function ($view) {
            if (session('sales')) {
                $sales = session('sales');
            }else {
                $sales = Sale::where('balance', "<", '0')->where('business', session('business_id'))->get();
            }
            $view   ->with('sales', $sales)->with('page', 'balances');
        });

        View::composer('sales_reports', function ($view) {
            $today = Carbon::today();
            $tomorrow = Carbon::tomorrow();
            $sales = Sale::where('business', session('business_id'))
                ->whereBetween('created_at', [$today, $tomorrow]);
            if (Auth::user()->level == 3){
                $sales = $sales->where('by_user', Auth::user()->id);
            }
            $sales = $sales->get();

            $users = User::where('business', session('business_id'))->get();

            if (session('sales')){
                $sales = session('sales');
                $view->with('sales', $sales)->with('users', $users)->with('page', "sReports");
            }else {
                $view->with('sales', $sales)->with('users', $users)->with('page', "sReports");
            }
        });

        View::composer('transactions.index',function($view){

            $users = User::where('business', session('business_id'))->get();

            Session::flash('_old_input',session('_old_input'));

            if (session('receipts')){
                $receipts = session('receipts');
                $view->with('receipts', $receipts)->with('users', $users);
            }else {
                $view->with('users', $users);
            }
        });

        View::composer('reports.categories_report',function($view) {
            if (session('sales_items')){
                $sales_items = session('sales_items');
                $view->with('sales_items', $sales_items);
            }else {
                $view;
            }
        });

            View::composer('settings', function ($view) {
            $business = Business::find(session('business_id'));
            $users = User::where('business', session('business_id'))->get()->count();
            $sales = Sale::where('business', session('business_id'))->get()->count();
            $customers = Customer::where('business', session('business_id'))->get()->count();
            $categories = Category::where('business', session('business_id'))->get();

            $view->with('business', $business)
                ->with('users', $users)
                ->with('sales', $sales)
                ->with('customers', $customers)
                ->with('categories', $categories)->with('page', 'settings');
        });


        //admin
        View::composer('admin/dashboard', function ($view) {
            $business = Business::all()->count();
            $users = User::where('access', 0)->get()->count();
            $sales = Sale::all()->count();
            $customers = Customer::all()->count();
            $suppliers = Supplier::all()->count();
            $view->with('business', $business)
                ->with('users', $users)
                ->with('sales', $sales)
                ->with('customers', $customers)
                ->with('suppliers', $suppliers)->with('page', 'home');
        });

        View::composer('admin/businesses', function ($view) {
            $businesses = Business::all();
            $view->with('businesses', $businesses)->with('page', 'businesses');
        });

        View::composer('transactions.transactions',function ($view){


           return $view->with('page','transactions')->with('customers');
        });

    }

    public function register()
    {
        // TODO: Implement register() method.
    }
}