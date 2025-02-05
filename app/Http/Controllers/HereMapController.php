<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HereMapController extends Controller
{
    public function showMap()
    {
        return view('map');
    }
}
