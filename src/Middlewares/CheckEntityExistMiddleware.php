<?php

namespace Megaads\Apify\Middlewares;

use Illuminate\Support\Facades\Schema;

class CheckEntityExistMiddleware
{

    public function handle($request, \Closure $next)
    {
        $routeInfo = $request->route();
        if (
            (
                !empty($routeInfo[2]['entity']) && strpos($routeInfo[2]['entity'], '.') !== false
            ) 
            || (
                !empty($routeInfo[2]['entity']) 
                && !$this->hasModel($routeInfo[2]['entity']) 
                && !Schema::hasTable($routeInfo[2]['entity'])
            )
        ) {
            return response()->json([
                'error' => '404 not found',
            ], 404);
        }

        return $next($request);
    }

    protected function hasModel($entity)
    {
        $modelNameSpace = env('APIFY_MODEL_NAMESPACE', 'App\Models');
        $entityClass = $modelNameSpace . '\\' . $entity;
        if (class_exists($entityClass)) {
            return true;
        } else {
            $entityClass = $modelNameSpace . '\\' . str_replace('_', '', ucwords($entity, '_'));
            if (class_exists($entityClass)) {
                return true;
            }
        }

        return false;
    }

}
