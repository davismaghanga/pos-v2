<?php

namespace App\Http\Controllers;

use App\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SalesReportsController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'userAccess']);
    }

    public function getIndex(){
        return view('sales_reports');
    }

    public function postFindBetweenDates(Request $request) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = $request->input('user');


        if ($user == 0) {
            $sales = Sale::where('business', session('business_id'))
                ->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($user != 0) {
            $sales = Sale::where('business', session('business_id'))
                ->where('by_user', $user)
                ->whereBetween('created_at', [$startDate, $endDate]);
        }

        $sales = $sales->get();

        return redirect('sReports')->withInput()->with('sales', $sales);
    }
}
