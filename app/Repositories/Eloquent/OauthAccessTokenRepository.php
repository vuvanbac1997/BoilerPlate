<?php
namespace App\Repositories\Eloquent;

use App\Models\OauthAccessToken;
use App\Repositories\OauthAccessTokenRepositoryInterface;

/**
 * @method \App\Models\OauthAccessToken[] getEmptyList()
 * @method \App\Models\OauthAccessToken[]|\Traversable|array all($order = null, $direction = null)
 * @method \App\Models\OauthAccessToken[]|\Traversable|array get($order, $direction, $offset, $limit)
 * @method \App\Models\OauthAccessToken create($value)
 * @method \App\Models\OauthAccessToken find($id)
 * @method \App\Models\OauthAccessToken[]|\Traversable|array allByIds($ids, $order = null, $direction = null, $reorder = false)
 * @method \App\Models\OauthAccessToken[]|\Traversable|array getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);
 * @method \App\Models\OauthAccessToken update($model, $input)
 * @method \App\Models\OauthAccessToken save($model);
 */
class OauthAccessTokenRepository extends SingleKeyModelRepository implements OauthAccessTokenRepositoryInterface
{
    public function getBlankModel()
    {
        return new OauthAccessToken();
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

    public function updateOldTokenRevoke($id, $userId, $clientId)
    {
        $model = $this->getBlankModel();
        $model->where('id', '<>', $id)
            ->where('user_id', $userId)
            ->where('client_id', $clientId)
            ->update(['revoked' => true]);

        return true;
    }
}
