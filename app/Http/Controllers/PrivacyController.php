<?php

namespace App\Http\Controllers;

class PrivacyController extends ApplicationController
{
    public function index(){
        return view('privacy.index');
    }

    public function terms(){
        return view('privacy.terms');
    }
}
