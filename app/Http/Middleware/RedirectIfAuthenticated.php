<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            
            $current_user = Auth::user();

            if($current_user->status == 'request'){
                return redirect()->route('waiting');    
            }

            if($current_user->hasRole('Invitado')){
                return redirect()->route('profile');
            }else{
                //aqui tendria que extraerlos de la db, porq puede hallan registrado nuevos
                //$user->hasRole(['editor', 'moderator'])
                return redirect()->route('dashboard');    
            }
        }

        return $next($request);
    }
}
