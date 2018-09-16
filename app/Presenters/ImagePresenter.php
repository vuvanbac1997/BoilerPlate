<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;

class ImagePresenter extends BasePresenter
{
    public function url()
    {
        if ($this->entity->is_local == false) {
            return $this->entity->url;
        }

        $config = config('file.categories.' . $this->entity->file_category_type);
        return \URLHelper::asset($config['local_path'] . $this->entity->url, $config['local_type']);
    }
}
