<?php namespace App\Observers;

use Illuminate\Support\Facades\Redis;

class UserNotificationObserver extends BaseObserver
{
    protected $cachePrefix = 'UserNotificationModel';

    public function created($model)
    {

    }

    public function updated($model)
    {

    }

    public function deleted($model)
    {

    }
}