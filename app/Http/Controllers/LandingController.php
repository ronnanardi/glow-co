<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    //
    public function index()
    {
        // view ini tampil untuk semua (guest & customer)
        return view('customer.landing');
    }
}
