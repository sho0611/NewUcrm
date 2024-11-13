<?php

namespace App\Http\Controllers;

use App\Models\Staff;


class GestController extends Controller
{
    public function viewStaff()
    {
        $staffs = Staff::all();
        return response()->json($staffs);    
    }
}
