<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Base;

class BaseRepository implements BaseRepositoryInterface
{
    protected $cacheEnabled = false;

    protected $cachePrefix = 'model';

    protected $cacheLifeTime = 60; // Minutes

    protected $querySearchTargets = [];

    public function getEmptyList()
    {
        return new Collection();
    }

    public function getModelClassName()
    {
        $model = $this->getBlankModel();

        return get_class($model);
    }

    public function getBlankModel()
    {
        return new Base();
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

    public function validator(array $data)
    {
        return \Validator::make($data, $this->rule());
    }

    public function all($order = null, $direction = null)
    {
        $model = $this->getModelClassName();
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;

            return $model::orderBy($order, $direction)->get();
        }

        return $model::all();
    }

    public function allEnabled($order = null, $direction = null)
    {
        $model = $this->getModelClassName();
        $query = $model::where('is_enabled', '=', true);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }

        return $query->get();
    }

    public function get($order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $model = $this->getModelClassName();

        return $model::orderBy($order, $direction)->skip($offset)->take($limit)->get();
    }

    public function getEnabled($order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $model = $this->getModelClassName();

        return $model::where('is_enabled', '=', true)->orderBy($order, $direction)->skip($offset)->take($limit)->get();
    }

    public function count()
    {
        $model = $this->getModelClassName();

        return $model::count();
    }

    public function countEnabled()
    {
        $model = $this->getModelClassName();

        return $model::where('is_enabled', '=', true)->count();
    }

    public function allByFilter($filter, $order = null, $direction = null)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
        $query = $this->buildOrder($query, $filter, $order, $direction);

        return $query->get();
    }

    public function getByFilter($filter, $order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
        $query = $this->buildOrder($query, $filter, $order, $direction);

        return $query->skip($offset)->take($limit)->get();
    }

    public function countByFilter($filter)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);

        return $query->count();
    }

    public function firstByFilter($filter)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);

        return $query->first();
    }

    public function getSQLByFilter($filter)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);

        return $query->toSql();
    }

    public function deleteByFilter($filter)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);

        return $query->delete();
    }

    public function getAPIArray($models)
    {
        $ret = [];
        foreach ($models as $model) {
            $ret[] = $model->toAPIArray();
        }

        return $ret;
    }

    public function pluck($collection, $value, $key = null)
    {
        $items = [];
        foreach ($collection as $model) {
            if (empty($key)) {
                $items[] = $model->$value;
            } else {
                $items[ $model->$key ] = $model->$value;
            }
        }

        return Collection::make($items);
    }

    /**
     * @param integer[] $ids
     *
     * @return string
     */
    protected function getCacheKey($ids)
    {
        $key = $this->cachePrefix;
        foreach ($ids as $id) {
            $key .= '-'.$id;
        }

        return $key;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string[]                           $orderCandidates
     * @param string                             $orderDefault
     * @param string                             $order
     * @param string                             $direction
     * @param int                                $offset
     * @param int                                $limit
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getWithQueryBuilder(
        $query,
        $orderCandidates = [],
        $orderDefault = 'id',
        $order,
        $direction,
        $offset,
        $limit
    ) {
        $order     = strtolower($order);
        $direction = strtolower($direction);
        $offset    = intval($offset);
        $limit     = intval($limit);
        $order     = in_array($order, $orderCandidates) ? $order : strtolower($orderDefault);
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        if ($limit <= 0) {
            $limit = 10;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        $query = $this->buildOrder($query, [], $order, $direction);

        return $query->offset($offset)->limit($limit)->get();
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param array                              $filter
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function buildQueryByFilter($query, $filter)
    {
        $tableName = $this->getBlankModel()->getTable();

        $query = $this->queryOptions($query);

        if (count($this->querySearchTargets) > 0 && array_key_exists('query', $filter)) {
            $searchWord = array_get($filter, 'query');
            if (!empty($searchWord)) {
                $query = $query->where(function($q) use ($searchWord) {
                    foreach ($this->querySearchTargets as $index => $target) {
                        if ($index === 0) {
                            $q = $q->where($target, 'LIKE', '%'.$searchWord.'%');
                        } else {
                            $q = $q->orWhere($target, 'LIKE', '%'.$searchWord.'%');
                        }
                    }
                });
            }
            unset($filter['query']);
        }

        if(array_key_exists('whereNotIn', $filter)) {
            $params = array_get($filter, 'whereNotIn');
            foreach ($params as $column => $values) {
                if (is_array($values)) {
                    $query = $query->whereNotIn($tableName.'.'.$column, $values);
                }
            }

            unset($filter['whereNotIn']);
        }

        foreach ($filter as $column => $value) {
            if (is_array($value)) {
                $query = $query->whereIn($tableName.'.'.$column, $value);
            } else {
                $query = $query->where($tableName.'.'.$column, $value);
            }
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param array                              $filter
     * @param string                             $order
     * @param string                             $direction
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function buildOrder($query, $filter, $order, $direction)
    {
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query     = $query->orderBy($order, $direction);
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function queryOptions($query)
    {
        return $query;
    }
}
