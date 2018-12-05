<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Jackiedo\LogReader\LogReader;


class LogsController extends Controller
{
    //
    protected $reader;

    public function __construct(LogReader $reader)
    {
        $this->reader = $reader;
    }

    public function index()
    {
        $reader = $this->reader->orderBy('date', 'desc')->get();
        return View::make('admin.logs.logs')->with('page','All Logs')->with('logs',$reader);
    }
}
