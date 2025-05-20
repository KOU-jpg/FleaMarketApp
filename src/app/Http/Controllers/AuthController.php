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

//会員登録ページ表示
  public function showRegisterForm()  { return view('auth/register');  }

//会員登録処理
  public function register(RegisterRequest $request)  {
    $form = $request->all();
    $form['password'] = Hash::make($form['password']);
    $user = User::create($form);
    Auth::login($user);
    return redirect()->route('mypage.profile.edit');}

//ログインページ表示
public function showLoginForm()  {  return view('auth/login');  }

//ログイン処理
  public function login(LoginRequest $request){
  $credentials = $request->only('email', 'password');
  // 認証成功：セッション再生成（セキュリティ対策）
  if (Auth::attempt($credentials)) {
      $request->session()->regenerate();
      return redirect('/');    }
  // 認証失敗：エラーメッセージを付けて元の画面に戻す
  return back()->withErrors([
      'auth.failed' => 'ログイン情報が登録されていません。',
  ])->withInput();}


//ログアウト処理
  public function logout(Request $request){
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
  }

//メール認証ページ表示
public function verifyEmail()  { return view('auth/email_verify');  }
}