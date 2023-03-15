<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(){

        return view('banner.banner');
    }
    public function create(){
        return view('banner.bannercreate');
    }
    public function store(Request $request)
    {
     
    }

}
