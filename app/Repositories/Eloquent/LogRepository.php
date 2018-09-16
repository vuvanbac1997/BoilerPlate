<?php namespace App\Repositories\Eloquent;

use \App\Repositories\LogRepositoryInterface;
use \App\Models\Log;

class LogRepository extends SingleKeyModelRepository implements LogRepositoryInterface
{
    protected $querySearchTargets = ['user_name', 'email', 'action', 'table', 'query'];

    public function getBlankModel()
    {
        return new Log();
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
