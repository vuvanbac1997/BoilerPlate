<?php

namespace App\Repositories\Eloquent;

use App\Repositories\SingleKeyModelRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Models\Log;

class SingleKeyModelRepository extends BaseRepository implements SingleKeyModelRepositoryInterface
{
    public function getPrimaryKey()
    {
        $model = $this->getBlankModel();

        return $model->getPrimaryKey();
    }

    public function find($id)
    {
        $modelClass = $this->getModelClassName();

        if( \CacheHelper::cacheRedisEnabled() ) {
            $tmp = explode('\\', $modelClass);
            $modelName = end($tmp);
            $cacheKey = \CacheHelper::keyForModel($modelName . 'Model');
            $cached = Redis::hget($cacheKey, $id);

            if( $cached ) {
                $object = new $modelClass(json_decode($cached, true));
                $object['attributes'] = json_decode($cached, true);
                $object['original']   = json_decode($cached, true);
                $object['exists']     = true;

                return $object;
            } else {
                $object = $modelClass::find($id);
                if( !empty($object) ) {
                    Redis::hsetnx($cacheKey, $id, $object);
                }

                return $object;
            }
        }

        $object = $modelClass::find($id);
        return $object;
    }

    public function allByIds($ids, $order = null, $direction = null, $reorder = false)
    {
        if (count($ids) == 0) {
            return $this->getEmptyList();
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        $query = $modelClass::whereIn($primaryKey, $ids);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }

        $models = $query->get();

        if (!$reorder) {
            return $models;
        }

        $result = $this->getEmptyList();
        $map = [];
        foreach ($models as $model) {
            $map[ $model->id ] = $model;
        }
        foreach ($ids as $id) {
            $model = $map[ $id ];
            if (!empty($model)) {
                $result->push($model);
            }
        }

        return $result;
    }

    public function countByIds($ids)
    {
        if (count($ids) == 0) {
            return 0;
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        return $modelClass::whereIn($primaryKey, $ids)->count();
    }

    public function getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null)
    {
        if (count($ids) == 0) {
            return $this->getEmptyList();
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        $query = $modelClass::whereIn($primaryKey, $ids);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }
        if (!is_null($offset) && !is_null($limit)) {
            $query = $query->offset($offset)->limit($limit);
        }

        return $query->get();
    }

    public function create($input)
    {
        \DB::connection()->enableQueryLog();

        $model = $this->getBlankModel();
        $model = $this->update($model, $input);

        $queries = \DB::getQueryLog();
        $query = $queries[count($queries) - 1];
        foreach( $query['bindings'] as $key => $value ) {
            $query['query'] = preg_replace("/\?/", "`$value`", $query['query'], 1);
        }

        if( \App::environment() != 'testing' ) {
            // crud actions must be execute by repository
            $admin = \Auth::guard('admins')->user();
            if( !empty($admin) ) {
                Log::create(
                    [
                        'user_name' => $admin->name,
                        'email'     => $admin->email,
                        'table'     => $model->getTable(),
                        'action'    => Log::TYPE_ACTION_INSERT,
                        'record_id' => $model->id,
                        'query'     => $query['query'],
                    ]
                );
            }
        }

        return $model;
    }

    public function update($model, $input)
    {
        foreach ($model->getEditableColumns() as $column) {
            if (array_key_exists($column, $input)) {
                $model->$column = array_get($input, $column);
            }
        }

        if( isset($model->id) && $model->id ) {
            \DB::connection()->enableQueryLog();
            $model = $this->save($model);
            if( !$model ) {
                return false;
            }

            if( count(\DB::getQueryLog()) ) {
                $queries = \DB::getQueryLog();
                $query = $queries[count($queries) - 1];
                foreach( $query['bindings'] as $key => $value ) {
                    $query['query'] = preg_replace("/\?/", "`$value`", $query['query'], 1);
                }

                if( \App::environment() != 'testing' ) {
                    // crud actions must be execute by repository
                    $admin = \Auth::guard('admins')->user();
                    if( !empty($admin) ) {
                        Log::create(
                            [
                                'user_name' => $admin->name,
                                'email'     => $admin->email,
                                'table'     => $model->getTable(),
                                'action'    => Log::TYPE_ACTION_UPDATE,
                                'record_id' => $model->id,
                                'query'     => $query['query'],
                            ]
                        );
                    }
                }
            }
        } else {
            $model = $this->save($model);
        }

        return $model;
    }

    public function save($model)
    {
        if (!$model->save()) {
            return false;
        }

        return $model;
    }

    public function delete($model)
    {
        \DB::connection()->enableQueryLog();
        $deleted = $model->delete();

        $queries = \DB::getQueryLog();
        $query = $queries[count($queries) - 1];
        foreach( $query['bindings'] as $key => $value ) {
            $query['query'] = preg_replace("/\?/", "`$value`", $query['query'], 1);
        }

        if( \App::environment() != 'testing' ) {
            // crud actions must be execute by repository
            $admin = \Auth::guard('admins')->user();

            if( !empty($admin) ) {
                Log::create(
                    [
                        'user_name' => $admin->name,
                        'email'     => $admin->email,
                        'table'     => $model->getTable(),
                        'action'    => Log::TYPE_ACTION_DELETE,
                        'record_id' => $model->id,
                        'query'     => $query['query'],
                    ]
                );
            }
        }

        return $deleted;
    }

    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'getBy')) {
            return $this->dynamicGet($method, $parameters);
        }

        if (Str::startsWith($method, 'allBy')) {
            return $this->dynamicAll($method, $parameters);
        }

        if (Str::startsWith($method, 'countBy')) {
            return $this->dynamicCount($method, $parameters);
        }

        if (Str::startsWith($method, 'findBy')) {
            return $this->dynamicFind($method, $parameters);
        }

        if (Str::startsWith($method, 'deleteBy')) {
            return $this->dynamicDelete($method, $parameters);
        }

        $className = static::class;
        throw new \BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }

    private function dynamicGet($method, $parameters)
    {
        $finder = substr($method, 5);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        $order = array_get($parameters, 0, 'id');
        $direction = array_get($parameters, 1, 'asc');
        $offset = array_get($parameters, 2, 0);
        $limit = array_get($parameters, 3, 10);

        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }
        if (!is_null($offset) && !is_null($limit)) {
            $query = $query->offset($offset)->limit($limit);
        }

        return $query->get();
    }

    private function dynamicAll($method, $parameters)
    {
        $finder = substr($method, 5);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        $order = array_get($parameters, 0, 'id');
        $direction = array_get($parameters, 1, 'asc');

        return $query->orderBy($order, $direction)->get();
    }

    private function dynamicCount($method, $parameters)
    {
        $finder = substr($method, 7);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->count();
    }

    private function dynamicFind($method, $parameters)
    {
        $finder = substr($method, 6);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->first();
    }

    private function dynamicDelete($method, $parameters)
    {
        $finder = substr($method, 8);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->delete();
    }
}
