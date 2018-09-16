<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;

class FilePresenter extends BasePresenter
{
    public function url()
    {
        if ($this->entity->is_local == false) {
            return $this->entity->url;
        }

        return \URL::to($this->entity->url);
    }
}
