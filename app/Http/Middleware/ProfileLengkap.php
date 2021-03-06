<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProfileLengkap
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::find(Auth::id());
        if(empty($user->photo)) {
            return redirect()->route('profile')->with('error', 'Mohon lengkapi data anda terlebih dahulu');
        }
        return $next($request);
    }
}
