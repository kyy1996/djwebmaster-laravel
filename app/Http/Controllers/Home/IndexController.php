<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\AppController;
use Illuminate\Http\Response;

class IndexController extends AppController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return view('home');
    }
}
