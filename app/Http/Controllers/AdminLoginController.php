<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
  
   
    public function logIn(AdminLoginRequest $request)
    {
        //dd($request);
        $credentials = $request->only('name', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
          
            $admin = Auth::guard('admin')->user(); 
              
            return response()->json([
                'message' => 'Login successful',
                'admin' => $admin 
            ]);
        }
    
        // 認証失敗時
        return response()->json([
            'message' => 'Invalid credentials'
        ]);
    }

  //ログアウト
    public function logOut(Request $request)
    {
        Auth::guard('admin')->logout();

        // セッション無効化とトークン再生成
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return response()->json([
            'message' => 'ログアウトしました。',
        ],);
    }
}
