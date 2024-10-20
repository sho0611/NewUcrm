<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'password' => 'required',
        ];
    }

  //失敗時に例外をスローすることで、エラーハンドリング
    public function authenticate(): void
    {
        //Laravelの認証システムを使用して、管理者のログインを試みる。
        if (!Auth::guard('admin')->attempt($this->only('name', 'password'))) {
            //エラー処理
            throw ValidationException::withMessages(['failed' => __('auth.failed')]);
        }
    }
}
