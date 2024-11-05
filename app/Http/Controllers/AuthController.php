<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * ユーザー登録
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            Log::info(get_class($user));
                          //ここでUndefined method 'createToken'が発生してます
            $token = $user->createToken('AccessToken')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => '認証に失敗しました。']);
        }
    }


    /**
     * ログアウト
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'ログアウトしました。'], 200);
    }
}

