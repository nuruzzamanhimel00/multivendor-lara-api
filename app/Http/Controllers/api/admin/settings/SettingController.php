<?php

namespace App\Http\Controllers\api\admin\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function store(Request $request){
        return $request->all();
    }
}
