<?php

namespace App\Helpers\Production;

use App\Helpers\CacheHelperInterface;

class CacheHelper implements CacheHelperInterface
{
    public $appPrefix = 'boilerplate';

    public function cacheRedisEnabled()
    {
        return ( env('CACHE_ENABLED') && env('CACHE_DRIVER') == 'redis' ) ? true : false;
    }


    /**
     * generate redis key for model
     *
     * @params  string  $model  ex: UserModel
     *
     * */
    public function keyForModel($model)
    {
        $model  = \StringHelper::camel2Spinal($model);

        return implode('_', [$this->appPrefix, $model]);
    }

    /**
     * generate redis key for method
     *
     * @params  string  $class  ex: UserRepository
     *          string  $method ex: getAllFriendInProvince
     *          array   [5, 200]
     * */
    public function keyForMethod($class, $method, $params = [])
    {
        $class  = \StringHelper::camel2Spinal($class);
        $method = \StringHelper::camel2Spinal($method);
        $params = implode('-', $params);

        return implode('_', [$this->appPrefix, $class, $method, $params]);
    }
}
