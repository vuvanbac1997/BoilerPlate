<?php namespace App\Repositories\Eloquent;

use \App\Repositories\TestRepositoryInterface;
use \App\Models\Test;

class TestRepository extends SingleKeyModelRepository implements TestRepositoryInterface
{

    public function getBlankModel()
    {
        return new Test();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

}
