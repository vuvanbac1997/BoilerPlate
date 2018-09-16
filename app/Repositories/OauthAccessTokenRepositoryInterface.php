<?php
namespace App\Repositories;

interface OauthAccessTokenRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param int $id
     * @param int $userId
     * @param int $clientId
     *
     * @return mixed
     */
    public function updateOldTokenRevoke($id, $userId, $clientId);
}
