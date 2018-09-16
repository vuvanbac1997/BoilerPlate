<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;

class UserNotificationPresenter extends BasePresenter
{
    public function userName()
    {
        if ($this->entity->user_id == 0) {
            return 'Broadcast';
        }

        $user = $this->entity->user;
        if (empty($user)) {
            return 'Unknown';
        }

        return $user->name;
    }
}
