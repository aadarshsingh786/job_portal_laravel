<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This Model For Index Page
    public function index(){
        return view('frontend.home');
    }

    public function contact(){
        return view('frontend.contact');
    }
}
