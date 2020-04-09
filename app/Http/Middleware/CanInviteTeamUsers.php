<?php

namespace App\Http\Middleware;

use App\Shop;
use Closure;
use Illuminate\Support\Facades\Cache;

class CanInviteTeamUsers
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

        if (Cache::has('shop_id')) {

                return $next($request);

        }else{
            return redirect()->route('team.users');
        }

    }
}
