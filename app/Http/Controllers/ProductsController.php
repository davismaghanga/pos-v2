<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;

class ProductsController extends Controller
{

    public function __construct(){
        $this->middleware(['auth', 'userAccess']);
    }

    public function getIndex(){
        return view('products');
    }

    public function postCreate(Request $request) {

        $data = array(  'name' => $request->input('name'),
                        'code' => $request->input('code'),
                        'price' => $request->input('price'),
                        'category' => $request->input('category'),
                        'supplier' => $request->input('supplier'),
                        'stock' => $request->input('stock'));

        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'price' => 'numeric',
            'stock' => 'numeric'
        ], [
            'category.required' => 'Please select a category'
        ]);

        if($validator->fails()){
            return redirect('products')
                ->withErrors($validator)
                ->withInput()
                ->with('createProduct', true);
        }

        $product = new Product();
        $product->business = session('business_id');
        $product->name = $data['name'];
        $product->code = $data['code'] == "" ? 0 : $data['code'];
        $product->price = $data['price'];
        $product->category = $data['category'];
        $product->supplier = $data['supplier'];
        $product->stock = $data['stock'];
        $product->save();

        return redirect('products');
    }

    public function postCreateCategory(Request $request) {
        $data = array('name' => $request->input('name'));

        $validator = Validator::make($request->all(), [
            'name' => 'unique:categories,name,NULL,business,business,' . session('business_id')
        ], [
            'name' => 'A category of this name already exists'
        ]);

        if($validator->fails()){
            return redirect('products')
                ->withErrors($validator)
                ->withInput()
                ->with('createCategory', true);
        }

        $category = new Category();
        $category->business = session('business_id');
        $category->name = $data['name'];
        $category->save();

        return redirect('products');
    }

    public function postAddStock(Request $request) {
        $validator = Validator::make($request->all(), [
            'add_stock_quantity' => 'numeric|min:1'
        ]);

        if($validator->fails()){
            return redirect('products')
                ->withErrors($validator)
                ->withInput()
                ->with('addStock', true);
        }

        $pro = Product::find($request->input('add_stock_id'));
        $pro->stock += $request->input('add_stock_quantity');
        $pro->save();

        return redirect('products');
    }

    public function getProduct($id){
        $pro = Product::find($id);
        return array($pro->code, $pro->category, $pro->price, $pro->stock);
    }

    public function postEdit(Request $request) {
        $data = array(  'name' => $request->input('edit_name'),
            'code' => $request->input('edit_code'),
            'price' => $request->input('edit_price'),
            'category' => $request->input('edit_category'),
            'supplier' => $request->input('edit_supplier'),
            'stock' => $request->input('edit_stock'));

        $validator = Validator::make($request->all(), [
            'edit_category' => 'required',
            'edit_price' => 'numeric',
            'edit_stock' => 'numeric'
        ], [
            'edit_category.required' => 'Please select a category'
        ]);

        if($validator->fails()){
            return redirect('products')
                ->withErrors($validator)
                ->withInput()
                ->with('editProduct', true);
        }

        $product = Product::find($request->input('edit_id'));
        $product->name = $data['name'];
        $product->code = $data['code'] == "" ? 0 : $data['code'];
        $product->price = $data['price'];
        $product->category = $data['category'];
        $product->supplier = $data['supplier'];
        $product->stock = $data['stock'];
        $product->save();

        return redirect('products');
    }

    public function getDelete($id) {
        Product::destroy($id);
        return redirect('products');
    }

    public function postSearch(Request $request) {
        $value = $request->input('search');
        $result = Product::like('name', $value)->get();

        return redirect('products')->withInput()->with('products', $result);
    }
}
