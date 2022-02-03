<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /** 
     * @return View
    */
    public function showLogin()
        {
            return view('login.login_form');
        }

    /**
     * @param App\Http\Requests\LoginFormRequest
     * $request
     */

    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');

        //1,アカウントがロックされていたら弾く
        $user = $this->user->getUserByEmail($credentials['email']);

        if(!is_null($user)){
            if ($this->user->isAccountLocked($user)){
                return back()->withErrors([
                    'danger' => 'アカウントがロックされてます。',
                ]);
            }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
                //２、成功したらエラーアカウントを０にする
                $this->user->resetErrorCount($user);
            return redirect()->route('home')->with('success', 'ログイン成功しました!');
        }
            //3.ログインが失敗したらエラーカウントを１増やす
            $user->error_count = $this->user->addErrorCount($user->error_count);

            //4,エラーカウントが６以上の場合はアカウントロックする
            if ($this->user->lockAccount($user)){
                return back()->withErrors([
                    'danger' => 'アカウントがロックされました。解除したい場合は運営者に連絡ください。',
                ]);
            }
            $user->save();
        
    }

        return back()->withErrors([
            'danger' => 'メールアドレスかパスワードが間違ってます。',
        ]);
    }
    /**
     * ユーザーをアプリケーションからログアウトさせる
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login.show')->with('danger', 'ログアウトしました!');
    }
}
