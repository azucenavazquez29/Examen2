<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControllerEmpleado extends Controller
{
      public function index(){
        return view('empleado');
    }
}
