<?php
namespace Megaads\Apify\Controllers;

use Illuminate\Http\Request;
use Megaads\Apify\Controllers\BaseController;

class APIController extends BaseController
{
    public function get($entity, Request $request)
    {
        $response = [];
        $queryParams = $this->buildQueryParams($request, $entity);
        $model = $this->getModel($entity);
        $model = $this->buildSortQuery($model, $queryParams['sorts'], $entity);
        $model = $this->buildSelectionQuery($model, $queryParams['fields'], $entity);
        $model = $this->buildFilterQuery($model, $queryParams['filters'], $entity);
        $model = $this->buildGroupQuery($model, $queryParams['groups'], $entity);
        $model = $this->buildEmbedQuery($model, $queryParams['embeds'], $entity);
        if ($queryParams['metric'] == 'count'
            || $queryParams['metric'] == 'first') {
            $response['result'] = $this->fetchData($model, $queryParams);
        } else {
            $response['meta'] = $this->fetchMetaData($model, $queryParams);
            $response['result'] = $this->fetchData($model, $queryParams);
        }
        return $this->success($response);
    }

    public function show($entity, $id, Request $request)
    {
        $queryParams = $this->buildQueryParams($request, $entity);
        $model = $this->getModel($entity);
        $model = $this->buildEmbedQuery($model, $queryParams['embeds'], $entity);
        $result = $model->find($id);
        return $this->success([
            'result' => $result,
        ]);
    }

    public function store($entity, Request $request)
    {
        $result = null;
        $model = $this->getModel($entity);
        $attributes = $request->all();
        try {
            $result = $model->create($attributes);
        } catch (\Exception $exc) {
            return $this->error([
                'result' => $exc->getMessage()
            ]);
        }
        return $this->success([
            'result' => $result,
        ]);
    }

    public function update($entity, $id, Request $request)
    {
        $model = $this->getModel($entity);
        $attributes = $request->all();
        $obj = $model->find($id);
        if ($obj == null) {
            return $this->error([
                'result' => '404',
            ]);
        }
        $result = $obj->update($attributes);
        return $this->success([
            'result' => $result,
        ]);
    }

    public function patch($entity, $id, Request $request)
    {
        $model = $this->getModel($entity);
        $attributes = $request->all();
        $obj = $model->find($id);
        if ($obj == null) {
            return $this->error([
                'result' => '404',
            ]);
        }
        $result = $obj->update($attributes);
        return $this->success([
            'result' => $result,
        ]);
    }

    public function destroy($entity, $id, Request $request)
    {
        $model = $this->getModel($entity);
        $obj = $model->find($id);
        if ($obj == null) {
            return $this->error([
                'result' => '404',
            ]);
        }
        $result = $obj->delete();
        return $this->success([
            'result' => $result,
        ]);
    }
}
