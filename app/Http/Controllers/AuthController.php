<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * ログイン, ログイン履歴を作成 
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function logIn(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'staff_id' => $request->staff_id])) {  

        /** @var \App\Models\Account */
            $user = Auth::user();
            
            LoginHistory::create([
                'staff_id' => $user->staff_id,  
                'account_id' =>  $user->id,
                'login_time' => Carbon::now(),  
            ]); 

            $token = $user->createToken('AccessToken')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => '認証に失敗しました。']);
        }
    }

    /**
     * ログアウト, ログアウト時間を作成
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function logout(Request $request)
    {
    
    /** @var \App\Models\Account */
    $user = Auth::user();
    $staffId = $user->staff_id;
    $accountId = $user->id;

    $history = $this->getHistory($staffId, $accountId);
    $history->update([
        'logout_time' => Carbon::now(),
    ]);

    if (!$history) {
        return response()->json(['error' => 'ログイン履歴が見つかりませんでした。']);
    }
    
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'ログアウトしました']);  
    }

    /**
     * 更新をするログイン履歴を取得
     *
     * @param $staffId スタッフID
     * @param $accountId アカウントID
     * @return LoginHistory ログイン履歴
     */ 
    private function getHistory($staffId, $accountId)
    {
        return LoginHistory::where('staff_id', $staffId)
        ->where('account_id', $accountId)
        ->latest('login_time')   
        ->first();
    }   
}

