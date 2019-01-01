<?php

namespace App\Http\Middleware;

use Closure;

use Session;

use App;

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
        $lang = $request->route('lang');
        if(session()->has('lang')){
            session()->forget('lang');
        }
        if(!empty($lang) && in_array($lang, self::LANGUAGES)){
             App::setLocale($lang);
             session(['lang' => $lang]);
        }
        return $next($request);
    }
}
