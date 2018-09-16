<?php
namespace App\Repositories\Eloquent;

use App\Models\OauthClient;
use App\Repositories\OauthClientRepositoryInterface;

/**
 * @method \App\Models\OauthClient[] getEmptyList()
 * @method \App\Models\OauthClient[]|\Traversable|array all($order = null, $direction = null)
 * @method \App\Models\OauthClient[]|\Traversable|array get($order, $direction, $offset, $limit)
 * @method \App\Models\OauthClient create($value)
 * @method \App\Models\OauthClient find($id)
 * @method \App\Models\OauthClient[]|\Traversable|array allByIds($ids, $order = null, $direction = null, $reorder = false)
 * @method \App\Models\OauthClient[]|\Traversable|array getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);
 * @method \App\Models\OauthClient update($model, $input)
 * @method \App\Models\OauthClient save($model);
 */
class OauthClientRepository extends SingleKeyModelRepository implements OauthClientRepositoryInterface
{
    protected $querySearchTargets = ['name', 'secret', 'redirect'];

    public function getBlankModel()
    {
        return new OauthClient();
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
