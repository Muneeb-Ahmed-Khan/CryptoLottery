<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function __construct()
    {
        /*Only GUESTS can use it. any authorized person that uses it will be redirected to Authenticate.php Middleware
        and it will return it to the ReturnToUnauthorizedPage route and that will redirect it accoring to it's gurad */
        $this->middleware('guest');
        $this->middleware('guest:company');
        $this->middleware('guest:admin');
        $this->middleware('guest:user');
    }

    public function index()
    {
        return view('index');
    }

    public function mobileIndex()
    {
        return view('mobileIndex');
    }

    public function about() {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function copyright() {
        return view('copyright');
    }

    public function dmca()
    {
        return view('dmca');
    }

    public function faq() {
        return view('faq');
    }

    public function register()
    {
        return view('register');
    }

    public function terms() {
        return view('terms');
    }
}
