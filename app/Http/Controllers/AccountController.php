<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    // This method Will show user registration page
    public function registration(){
        return view('frontend.account.registration');
    }

    // This method Will show user login page
    public function login(){
        return view('frontend.account.login');
    }
}
