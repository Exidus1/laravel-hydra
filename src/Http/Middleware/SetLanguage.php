<?php

namespace Exidus\Hydra\Http\Middleware;

use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class SetLanguage
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $type
     * @return mixed
     */
    public function handle($request, Closure $next, string $type)
    {
        $locale = false;

        // Read config
        $header = config('hydra.language.header');
        $param = config('hydra.language.param');

        // Read language setter
        if ($request->hasHeader($header)) {
            $locale = $request->header($header);
        } elseif ($request->exists($param)) {
            $locale = $request->input($param);
        }

        // If this is web request with session available
        if ($type == 'web') {
            if ($locale) {
                $request->session()->put('locale', $locale);
            } elseif ($request->session()->has('locale')) {
                $locale = $request->session()->get('locale');
            }
        }

        // If a locale has been set & exists
        if ($locale && file_exists(resource_path('lang/' . $locale))) {
            App::setLocale($locale);
            Carbon::setLocale($locale);
        }

        return $next($request);
    }

}
