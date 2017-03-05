<?php

namespace Recca0120\Repository;

use Illuminate\Database\Eloquent\Model;
use Recca0120\Repository\Compilers\EloquentCompiler;

class EloquentRepository extends AbstractRepository
{
    /**
     * $converter.
     *
     * @var string
     */
    protected $compiler = EloquentCompiler::class;

    /**
     * __construct.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * get.
     *
     * @param \Recca0120\Repository\Criteria|array $criteria
     * @param int $limit
     * @param int $offset
     * @return \Illuminate\Support\Collection
     */
    public function get($criteria = [], $columns = ['*'], $limit = null, $offset = null)
    {
        $model = $this->match($criteria);

        if (is_null($limit) === false) {
            $model = $model->take($limit);
        }

        if (is_null($offset) === false) {
            $model = $model->skip($offset);
        }

        return $model->get($columns);
    }

    /**
     * paginate.
     *
     * @param mixed $criteria
     * @param string $perPage
     * @param int $pageName
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($criteria = [], $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $model = $this->match($criteria);
        $perPage = $perPage ?: $this->perPage;

        return $model->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * simplePaginate.
     *
     * @param mixed $criteria
     * @param string $perPage
     * @param int $pageName
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($criteria = [], $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $model = $this->match($criteria);
        $perPage = $perPage ?: $this->perPage;

        return $model->simplePaginate($perPage, $columns, $pageName, $page);
    }

    /**
     * first.
     *
     * @param \Recca0120\Repository\Criteria|array $criteria
     * @return mixed
     */
    public function first($criteria = [], $columns = ['*'])
    {
        $model = $this->match($criteria);

        return $model->first($columns);
    }

    /**
     * find.
     *
     * @param int $id
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $model = $this->cloneModel();
        $model = ($model instanceof Model) ? $model : $model->getModel();

        return $model->find($id);
    }

    /**
     * newInstance.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent
     */
    public function newInstance($attributes = [])
    {
        $model = $this->cloneModel();
        $model = ($model instanceof Model) ? $model : $model->getModel();

        return $model->forceFill($attributes);
    }

    /**
     * create.
     *
     * @param array $attributes
     * @param bool $forceFill
     * @return mixed
     */
    public function create($attributes, $forceFill = false)
    {
        $model = $this->newInstance();
        $model = ($forceFill === false) ? $model->fill($attributes) : $model->forceFill($attributes);
        $model->save();

        return $model;
    }

    /**
     * update.
     *
     * @param array $attributes
     * @param int $id
     * @param bool $forceFill
     * @return mixed
     */
    public function update($attributes, $id, $forceFill = false)
    {
        $model = $this->find($id);
        $model = ($forceFill === false) ? $model->fill($attributes) : $model->forceFill($attributes);
        $model->save();

        return $model;
    }

    /**
     * delete.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * destroy.
     *
     * @param \Recca0120\Repository\Criteria|array $criteria
     * @return int
     */
    public function destroy($criteria = [])
    {
        $model = $this->match($criteria);

        return $model->delete();
    }
}
