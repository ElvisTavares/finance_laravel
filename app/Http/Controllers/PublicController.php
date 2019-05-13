<?php

namespace App\Http\Controllers;

class PublicController extends ApplicationController
{

    function home() {
        return view('/home');
    }

}