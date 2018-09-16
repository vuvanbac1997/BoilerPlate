<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends AuthenticatableRepository implements UserRepositoryInterface
{
    protected $querySearchTargets = ['name', 'email', 'telephone', 'address', 'locale', 'birthday'];

    public function getBlankModel()
    {
        return new User();
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
