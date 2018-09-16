<?php namespace App\Observers;

use Illuminate\Support\Facades\Redis;

class AdminUserNotificationObserver extends BaseObserver
{
    protected $cachePrefix = 'AdminUserNotificationModel';

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