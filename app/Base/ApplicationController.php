<?php

namespace App\Http\Controllers;

use Auth;
use App\Account;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApplicationController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $root = '/';

    public function rootRedirect(){
        return redirect(url($this->root));
    }
}
