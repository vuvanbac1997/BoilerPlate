<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AdminUserRepositoryInterface;
use App\Models\AdminUser;

class AdminUserRepository extends AuthenticatableRepository implements AdminUserRepositoryInterface
{
    protected $querySearchTargets = ['name', 'email'];

    public function getBlankModel()
    {
        return new AdminUser();
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
