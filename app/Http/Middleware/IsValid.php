<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->session()->all();
        if(isset($session['id']) &&
        $user_details = DB::table('users as u')
                ->select(
                    'u.id',
                    'u.password',
                    'u.username',
                    'r.name as role_name',
                    )
                ->where('u.id','=',$session['id'])
                ->join('roles as r','u.role_id','r.id')
                ->first()
                ){
        }else{
            return redirect()->route('login'); 
        }
        return $next($request);
    }
}
