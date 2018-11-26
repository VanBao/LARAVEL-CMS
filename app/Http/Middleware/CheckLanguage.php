<?php

namespace App\Http\Middleware;

use Closure;

use Session;

class CheckLanguage
{
    const LANGUAGES = array("vn", "en");

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = $request->query('lang');
        if(!empty($lang) && in_array($lang, self::LANGUAGES)){
             App::setLocale($lang);
             session(['lang' => $lang]);
        }
        return $next($request);
    }
}
