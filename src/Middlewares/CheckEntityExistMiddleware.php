<?php

namespace Megaads\Apify\Middlewares;

use Illuminate\Support\Facades\Schema;

class CheckEntityExistMiddleware
{

    public function handle($request, \Closure $next)
    {
        $routeInfo = $request->route();
        if (!empty($routeInfo[2]['entity']) && !Schema::hasTable($routeInfo[2]['entity'])) {
            return response()->json([
                'error' => '404 not found',
            ], 404);
        }

        return $next($request);
    }

}
