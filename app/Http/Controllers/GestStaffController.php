<?php

namespace App\Http\Controllers;

use App\Models\Staff;




class GestStaffController extends Controller
{
    /**
     * スタッフ情報を表示
     *
     * @return \Illuminate\Http\Response
     */ 
    public function viewStaff()
    {
        $staffs = Staff::all();
        return response()->json($staffs);    
    }   
}
