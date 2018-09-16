<?php namespace App\Observers;

use Illuminate\Support\Facades\Redis;

class UserObserver extends BaseObserver
{
    protected $cachePrefix = 'UserModel';

    public function created($user)
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel($this->cachePrefix);
            Redis::hsetnx($cacheKey, $user->id, $user);
        }
    }

    public function updated($user)
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel($this->cachePrefix);
            Redis::hset($cacheKey, $user->id, $user);
        }
    }

    public function deleted($user)
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel($this->cachePrefix);
            Redis::hdel($cacheKey, $user->id);
        }
    }
}