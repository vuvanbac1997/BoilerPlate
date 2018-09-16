<?php
namespace App\Repositories\Eloquent;

use App\Models\OauthRefreshToken;
use App\Repositories\OauthRefreshTokenRepositoryInterface;

/**
 * @method \App\Models\OauthRefreshToken[] getEmptyList()
 * @method \App\Models\OauthRefreshToken[]|\Traversable|array all($order = null, $direction = null)
 * @method \App\Models\OauthRefreshToken[]|\Traversable|array get($order, $direction, $offset, $limit)
 * @method \App\Models\OauthRefreshToken create($value)
 * @method \App\Models\OauthRefreshToken find($id)
 * @method \App\Models\OauthRefreshToken[]|\Traversable|array allByIds($ids, $order = null, $direction = null, $reorder = false)
 * @method \App\Models\OauthRefreshToken[]|\Traversable|array getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);
 * @method \App\Models\OauthRefreshToken update($model, $input)
 * @method \App\Models\OauthRefreshToken save($model);
 */
class OauthRefreshTokenRepository extends SingleKeyModelRepository implements OauthRefreshTokenRepositoryInterface
{
    public function getBlankModel()
    {
        return new OauthRefreshToken();
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

    public function updateOldAccessTokenRevoke($id, $accessTokenId)
    {
        $model = $this->getBlankModel();
        $model->where('id', '<>', $id)
            ->where('access_token_id', '<>', $accessTokenId)->first();
        $model->delete();

        return true;
    }
}
