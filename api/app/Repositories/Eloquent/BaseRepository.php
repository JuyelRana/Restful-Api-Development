<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\ModelNotDefinedException;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;
use Illuminate\Support\Arr;

abstract class BaseRepository implements IBase, ICriteria
{
    protected $model;

    /**
     * @throws ModelNotDefinedException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findWhereFirst($column, $value)
    {
        return $this->model->where($column, $value)->firstOrFail();
    }

    /**
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $record = $this->find($id);
        return $record->delete();
    }

    /**
     * @param ...$criteria
     * @return $this
     */
    public function withCriteria(...$criteria): BaseRepository
    {
        $criteria = Arr::flatten($criteria);

        foreach ($criteria as $criterion) {
            $this->model = $criterion->apply($this->model);
        }

        return $this;
    }


    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws ModelNotDefinedException
     */
    protected function getModelClass()
    {
        if (!method_exists($this, 'model')) {
            throw new ModelNotDefinedException();
        }

        return app()->make($this->model());
    }
}
