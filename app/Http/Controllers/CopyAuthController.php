<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CopyAuthController extends Controller
{
    /**
     * ユーザー登録
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function logIn(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        /** @var \App\Models\Account */
            $user = Auth::user();
            $token = $user->createToken('AccessToken')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => '認証に失敗しました。']);
        }
    }


    /**
     * ログアウト jsonでトークンを返す
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function logout(Request $request)
    {
        $header = getallheaders();
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'ログアウトしました', $header['Authorization'] ]);
    }
}

