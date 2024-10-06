<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class roleValidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_permission = Auth::user()->role;
        $have_permission = false;
        foreach(func_get_args() as $params) {
            if($params === $user_permission) {
                $have_permission = true;
            }
        }
        if($have_permission) {
            return $next($request);
        }
        if($user_permission == "user") {
            return redirect("/leads");
        }
        return redirect("/minhas/leads");
        // if(Auth::user()->role != $role) {
        //     return redirect("/leads");
        // }   
    }
}
