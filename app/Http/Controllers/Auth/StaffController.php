<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function dashboard()
    {
        return response()->json(['message' => 'Welcome to the staff dashboard', 'user' => Auth::user()]);
    }
}

