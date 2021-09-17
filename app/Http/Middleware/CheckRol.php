<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckRol
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
        //Determine if the current request is asking for JSON. This checks Content-Type equals application/json
        if($request->wantsJson() && Auth::user()->hasRole('Invitado')){
            //El usuario no tiene acceso al recurso, porque es un recursos solo para administradores
            return response()->json(['Session_error'=>'Session Expired'], 401);
        }

        if(!$request->wantsJson() && Auth::user()->hasRole('Invitado')){
            return redirect('/');
        }
        return $next($request);
    }
}
