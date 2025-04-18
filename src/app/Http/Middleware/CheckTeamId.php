<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckTeamId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // ログインしているかつ team_id が null の場合、企業登録画面へリダイレクトする
        if (Auth::check() && is_null(Auth::user()->team_id)) {
            return redirect()->route('company.first-register');
        }

        return $next($request);
    }
}
