<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
public function login()  {  return view('auth/login');  }
public function register()  { return view('auth/register');  }


  public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
}
public function register_user(RegisterRequest $request)  {
    $form = $request->all();
    $form['password'] = Hash::make($form['password']);
    $user = User::create($form);
    Auth::login($user);
    return redirect()->route('edit');
}

  public function login_user(LoginRequest $request){
    $credentials = $request->only('email', 'password');// バリデーション済みデータを取得
    // 認証を試みる
    
    if (Auth::attempt($credentials)) {
      // 認証成功：セッション再生成（セキュリティ対策）
        $request->session()->regenerate();
        return redirect('/');    }

    // 認証失敗：エラーメッセージを付けて元の画面に戻す
    return back()->withErrors([
        'auth.failed' => 'ログイン情報が登録されていません。',
    ])->withInput();}
}