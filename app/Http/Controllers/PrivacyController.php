<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function index(){
        return view('privacy.index');
    }
    public function terms(){
        return view('privacy.terms');
    }
}
