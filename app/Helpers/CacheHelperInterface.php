<?php

namespace App\Helpers;

interface CacheHelperInterface
{
    /**
     * Check is enabled cache & redis
     *
     * @params
     *
     * @return  boolean
     */
    public function cacheRedisEnabled();

    /**
     * generate redis key for model
     *
     * @params  string  $model  ex: UserModel
     *
     * */
    public function keyForModel($model);

    /**
     * generate redis key for method
     *
     * @params  string  $class  ex: UserRepository
     *          string  $method ex: getAllFriendInProvince
     *          array   [5, 200]
     * */
    public function keyForMethod($class, $method, $params = []);
}
