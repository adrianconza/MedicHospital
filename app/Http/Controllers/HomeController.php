<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('administrator');
    }

    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {
        return view('home');
    }
}
