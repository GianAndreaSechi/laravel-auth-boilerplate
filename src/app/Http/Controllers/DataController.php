<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function open(){
        return response()->json(["message"=>"open"]);
    }

    public function closed(){
        return response()->json(["message"=>"closed"]);
    }
}
