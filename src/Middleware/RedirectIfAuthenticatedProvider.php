<?php
namespace Oasis1992\Sociauth\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: gabriel_gerardo_rodriguez_diaz (oasis1992)
 * Date: 19/06/2016
 * Time: 3:51
 */


class RedirectIfAuthenticatedProvider 
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
        $state = $request->get('state');
        $request->session()->put('state',$state);
        $value = $request->session()->get('state');
        dd($value);
        if (Auth::check() == false) {
            return redirect('/login/facebook');
        }
        return $next($request);
    }
}