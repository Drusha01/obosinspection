<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IsAdministrator
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
            if($user_details->role_name == 'Administrator'){
        
            }elseif($user_details->role_name == 'Inspector Team Leader'){
                return redirect()->route('inspector-team-leader-dashboard'); 
            }elseif($user_details->role_name == 'Inspector'){
                return redirect()->route('inspector-dashboard'); 
            }
        }else{
            return redirect()->route('login'); 
        }
        return $next($request);
    }
}
