<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AuthenticatableRepositoryInterface;
use App\Models\AuthenticatableBase;

class AuthenticatableRepository extends SingleKeyModelRepository implements AuthenticatableRepositoryInterface
{
    public function getBlankModel()
    {
        return new AuthenticatableBase();
    }

    public function findByEmail($email, $includeDeleted = false)
    {
        $className = $this->getModelClassName();

        if( $includeDeleted ) {
            return $className::withTrashed()->where('email', $email)->first();
        } else {
            return $className::whereEmail($email)->first();
        }
    }

    public function findByFacebookId($facebookId)
    {
        $className = $this->getModelClassName();

        return $className::whereFacebookId($facebookId)->first();
    }
}
