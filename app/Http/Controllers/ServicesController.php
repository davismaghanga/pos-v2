<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Service;
use App\Category;

use App\Http\Requests;

class ServicesController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'userAccess']);
    }

    public function getIndex(){
        return view('services');
    }

    public function postCreate(Request $request) {

        $data = array(  'name' => $request->input('name'),
                        'category' => $request->input('category'),
                        'stock' => $request->input('stock'),
                        'charge' => $request->input('charge'));

        $validator = Validator::make($request->all(), [
            'name' => 'unique:services,name,NULL,business,business,' . session('business_id'),
            'charge' => 'numeric',
            'stock' => 'numeric',
            'category' => 'required'
        ], [
            'name' => 'A service with this name already exists.'
        ]);

        if($validator->fails()){
            return redirect('services')
                ->withErrors($validator)
                ->withInput()
                ->with('createService', true);
        }

        $service = new Service();
        $service->business = session('business_id');
        $service->name = $data['name'];
        $service->category = $data['category'];
        $service->stock = $data['stock'];
        $service->charge = $data['charge'];
        $service->save();

        return redirect('services');
    }

    public function postCreateCategory(Request $request) {
        $data = array('name' => $request->input('name'));

        $validator = Validator::make($request->all(), [
            'name' => 'unique:categories,name,NULL,business,business,' . session('business_id')
        ], [
            'name' => 'A category of this name already exists'
        ]);

        if($validator->fails()){
            return redirect('services')
                ->withErrors($validator)
                ->withInput()
                ->with('createCategory', true);
        }

        $category = new Category();
        $category->business = session('business_id');
        $category->name = $data['name'];
        $category->save();

        return redirect('services');
    }

    public function getService($id) {
        $serv = Service::find($id);
        return array($serv->category, $serv->charge,$serv->stock);
    }

    public function postEdit(Request $request) {

        $data = array(  'name' => $request->input('edit_name'),
            'category' => $request->input('edit_category'),
            'stock' => $request->input('edit_stock'),
            'charge' => $request->input('edit_charge'));

        $validator = Validator::make($request->all(), [
            'edit_charge' => 'numeric',
            'edit_stock' => 'numeric'
        ]);

        if($validator->fails()){
            return redirect('services')
                ->withErrors($validator)
                ->withInput()
                ->with('editService', true);
        }

        $service = Service::find($request->input('edit_id'));
        $service->name = $data['name'];
        $service->category = $data['category'];
        $service->charge = $data['charge'];
        $service->stock = $data['stock'];
        $service->save();

        return redirect('services');
    }

    public function getDelete($id) {
        Service::destroy($id);
        return redirect('services');
    }

    public function postSearch(Request $request) {
        $value = $request->input('search');
        $result = Service::like('name', $value)->get();

        return redirect('services')->withInput()->with('services', $result);
    }
}
