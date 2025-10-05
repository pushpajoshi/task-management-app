<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;


class AdminController extends Controller
{
    public function dashboard(){
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}