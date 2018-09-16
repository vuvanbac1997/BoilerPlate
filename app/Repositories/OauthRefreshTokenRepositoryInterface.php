<?php
namespace App\Repositories;

interface OauthRefreshTokenRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param int    $id
     * @param string $accessTokenId
     *
     * @return mixed
     */
    public function updateOldAccessTokenRevoke($id, $accessTokenId);
}
