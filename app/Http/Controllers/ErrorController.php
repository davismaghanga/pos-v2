<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ErrorController extends Controller
{
    //

    public function getUnauthorized() {
        $returnPage = Auth::user()->access == 0 ? 'home' : 'admin/home';
        return view('403')->with('returnPage', $returnPage);
    }
}
