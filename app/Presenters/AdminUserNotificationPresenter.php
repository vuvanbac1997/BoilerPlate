<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;

class AdminUserNotificationPresenter extends BasePresenter
{
    protected $multilingualFields = [];

    protected $imageFields = [];

    public function userName()
    {
        if ($this->entity->user_id == 0) {
            return 'Broadcast';
        }

        $user = $this->entity->adminUser;
        if (empty($user)) {
            return 'Unknown';
        }

        return $user->name;
    }
}
